<?php
/**
 * Topics API — CRUD for content_topics table
 *
 * GET              → List all topics (newest first)
 * POST action=create  → Create a new topic
 * POST action=update  → Update an existing topic
 * POST action=delete  → Delete a topic by ID
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db_config.php';

try {
    $pdo = getDbConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// ── GET: List all topics ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM content_topics ORDER BY created_at DESC");
    $topics = $stmt->fetchAll();
    $total  = count($topics);

    echo json_encode(['success' => true, 'total' => $total, 'topics' => $topics]);
    exit;
}

// ── POST: Create / Update / Delete ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['action'])) {
        echo json_encode(['success' => false, 'error' => 'Missing action']);
        exit;
    }

    $action = $input['action'];

    // ── CREATE ───────────────────────────────────────────────────────
    if ($action === 'create') {
        $title = trim($input['title'] ?? '');
        if ($title === '') {
            echo json_encode(['success' => false, 'error' => 'Title is required']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO content_topics (title, description, keywords, category, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $title,
            $input['description'] ?? null,
            $input['keywords']    ?? null,
            $input['category']    ?? null,
            $input['status']      ?? 'draft',
        ]);

        echo json_encode(['success' => true, 'id' => (int)$pdo->lastInsertId()]);
        exit;
    }

    // ── UPDATE ───────────────────────────────────────────────────────
    if ($action === 'update') {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID']);
            exit;
        }

        $title = trim($input['title'] ?? '');
        if ($title === '') {
            echo json_encode(['success' => false, 'error' => 'Title is required']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE content_topics SET title = ?, description = ?, keywords = ?, category = ?, status = ? WHERE id = ?");
        $stmt->execute([
            $title,
            $input['description'] ?? null,
            $input['keywords']    ?? null,
            $input['category']    ?? null,
            $input['status']      ?? 'draft',
            $id,
        ]);

        echo json_encode(['success' => true, 'updated' => $stmt->rowCount()]);
        exit;
    }

    // ── DELETE ───────────────────────────────────────────────────────
    if ($action === 'delete') {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM content_topics WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'deleted' => $stmt->rowCount()]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action: ' . $action]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
