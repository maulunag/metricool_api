<?php
/**
 * Data functions for the Socials Posts Viewer.
 * Handles loading posts, decisions, and saving decision data.
 * Uses composite key "source_file:row_number" to avoid collisions between files.
 */

define('DECISIONS_FILENAME', 'decisions_post.json');

/**
 * Get the path to the decisions file.
 */
function getDecisionsPath(string $jsonDir): string {
    return $jsonDir . '/' . DECISIONS_FILENAME;
}

/**
 * Get all JSON post files (excluding decisions).
 */
function getPostFiles(string $jsonDir): array {
    $files = glob($jsonDir . '/*.json');
    return array_values(array_filter($files, function($f) {
        return basename($f) !== DECISIONS_FILENAME;
    }));
}

/**
 * Extract ISO date from filename for sorting.
 * e.g. "posts_Thursday_2026-02-19T08:00:45.033-05:00.json" → "2026-02-19T08:00:45"
 */
function extractDateFromFilename(string $filename): string {
    if (preg_match('/(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})/', $filename, $m)) {
        return $m[1];
    }
    return '0000-00-00T00:00:00';
}

/**
 * Build an index of row_number|Slug → filename for resolving legacy decisions.
 */
function buildPostIndex(string $jsonDir): array {
    $files = getPostFiles($jsonDir);
    $index = [];
    foreach ($files as $f) {
        $data = json_decode(file_get_contents($f), true);
        if (!is_array($data)) continue;
        if (isset($data['Titulo'])) $data = [$data];
        $fname = basename($f);
        foreach ($data as $post) {
            $rn = $post['row_number'] ?? 0;
            $slug = $post['Slug'] ?? '';
            $index[$rn . '|' . $slug] = $fname;
        }
    }
    return $index;
}

/**
 * Load all decisions from decisions_post.json.
 * For legacy entries without source_file, resolves the correct file
 * using the post index (row_number + Slug).
 */
function loadDecisions(string $jsonDir): array {
    $path = getDecisionsPath($jsonDir);
    $raw = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    if (!is_array($raw)) $raw = [];

    // Build index to resolve legacy entries
    $postIndex = buildPostIndex($jsonDir);

    $map = [];
    $approved = [];
    $rejected = [];
    $sentMetricool = [];

    foreach ($raw as $d) {
        $sf = $d['source_file'] ?? null;
        $rn = $d['row_number'] ?? 0;

        // Legacy entry: resolve source_file from post index
        if (!$sf) {
            $slug = $d['Slug'] ?? '';
            $lookupKey = $rn . '|' . $slug;
            $sf = $postIndex[$lookupKey] ?? null;
        }

        // Index by composite key (only if we know the file)
        if ($sf) {
            $map[$sf . ':' . $rn] = $d['decision'];
        }

        if ($d['decision'] === 'approved') $approved[] = $d;
        elseif ($d['decision'] === 'rejected') $rejected[] = $d;
        elseif ($d['decision'] === 'send_metricool') $sentMetricool[] = $d;
    }

    return [
        'all'            => $raw,
        'map'            => $map,
        'approved'       => $approved,
        'rejected'       => $rejected,
        'sentMetricool'  => $sentMetricool,
    ];
}

/**
 * Load pending posts grouped by source file.
 * Returns ['byFile' => [...], 'total' => int]
 */
function loadPendingPosts(string $jsonDir, array $decisionMap): array {
    $files = getPostFiles($jsonDir);
    // Sort newest first by embedded ISO timestamp in filename
    usort($files, function($a, $b) {
        $dateA = extractDateFromFilename(basename($a));
        $dateB = extractDateFromFilename(basename($b));
        return strcmp($dateB, $dateA); // descending
    });
    $byFile = [];
    $total = 0;

    foreach ($files as $f) {
        $data = json_decode(file_get_contents($f), true);
        if (!is_array($data)) continue;
        if (isset($data['Titulo'])) $data = [$data]; // single post object

        $fname = basename($f);
        $pending = [];

        foreach ($data as $post) {
            $rowId = $post['row_number'] ?? 0;
            $compositeKey = $fname . ':' . $rowId;

            if (!isset($decisionMap[$compositeKey])) {
                $pending[] = $post;
                $total++;
            }
        }

        if (!empty($pending)) {
            $byFile[$fname] = array_reverse($pending);
        }
    }

    return ['byFile' => $byFile, 'total' => $total];
}

/**
 * Find a post by row_number and optional source_file across all JSON files.
 */
function findPostByRowNumber(string $jsonDir, int $rowNumber, ?string $sourceFile = null): ?array {
    $files = getPostFiles($jsonDir);

    // If source_file is provided, search in that file first
    if ($sourceFile) {
        $targetPath = $jsonDir . '/' . $sourceFile;
        if (file_exists($targetPath)) {
            $data = json_decode(file_get_contents($targetPath), true);
            if (is_array($data)) {
                foreach ($data as $p) {
                    if (isset($p['row_number']) && $p['row_number'] == $rowNumber) {
                        return $p;
                    }
                }
            }
        }
    }

    // Fallback: search all files
    foreach ($files as $f) {
        $data = json_decode(file_get_contents($f), true);
        if (!is_array($data)) continue;
        foreach ($data as $p) {
            if (isset($p['row_number']) && $p['row_number'] == $rowNumber) {
                return $p;
            }
        }
    }
    return null;
}

/**
 * Save a decision (approve/reject) for a post.
 * Returns JSON response array.
 */
function saveDecision(string $jsonDir, array $input): array {
    if (!isset($input['row_number']) || !isset($input['decision'])) {
        return ['success' => false, 'error' => 'Missing row_number or decision'];
    }

    $rowNumber = $input['row_number'];
    $sourceFile = $input['source_file'] ?? null;

    $path = getDecisionsPath($jsonDir);
    $decisions = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    if (!is_array($decisions)) $decisions = [];

    // Update existing or add new
    $found = false;
    foreach ($decisions as &$d) {
        $matchByComposite = $sourceFile && 
                            isset($d['source_file']) && 
                            $d['source_file'] === $sourceFile && 
                            $d['row_number'] == $rowNumber;
        $matchByLegacy = !$sourceFile && $d['row_number'] == $rowNumber && !isset($d['source_file']);

        if ($matchByComposite || $matchByLegacy) {
            $d['decision'] = $input['decision'];
            $d['updated_at'] = date('Y-m-d\TH:i:s');
            $found = true;
            break;
        }
    }
    unset($d);

    if (!$found) {
        $originalPost = findPostByRowNumber($jsonDir, (int)$rowNumber, $sourceFile);
        $entry = $originalPost ?? ['row_number' => $rowNumber];
        if ($sourceFile) {
            $entry['source_file'] = $sourceFile;
        }
        $entry['decision'] = $input['decision'];
        $entry['created_at'] = date('Y-m-d\TH:i:s');
        $entry['updated_at'] = date('Y-m-d\TH:i:s');
        $decisions[] = $entry;
    }

    file_put_contents($path, json_encode($decisions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return ['success' => true, 'total' => count($decisions)];
}

/**
 * Get the CSS class for a post status.
 */
function getStatusClass(string $status): string {
    $s = strtolower($status);
    if (strpos($s, 'done') !== false || strpos($s, 'up') !== false) return 'status-done';
    if (strpos($s, 'pend') !== false || strpos($s, 'draft') !== false) return 'status-pending';
    return 'status-default';
}

/**
 * Load all view data needed for rendering.
 */
function loadViewData(string $jsonDir, string $currentView): array {
    $decisions = loadDecisions($jsonDir);
    $pending = loadPendingPosts($jsonDir, $decisions['map']);

    return [
        'currentView'      => $currentView,
        'pendingByFile'    => $pending['byFile'],
        'countPending'     => $pending['total'],
        'approvedPosts'    => $decisions['approved'],
        'countApproved'    => count($decisions['approved']),
        'rejectedPosts'    => $decisions['rejected'],
        'countRejected'    => count($decisions['rejected']),
        'sentPosts'        => $decisions['sentMetricool'],
        'countSent'        => count($decisions['sentMetricool']),
    ];
}
