<?php
/**
 * Socials Posts Viewer — Entry Point
 * 
 * Handles:
 *   POST → Save decision (approve/reject) via AJAX
 *   GET  → Render the posts viewer UI
 */

require_once __DIR__ . '/includes/functions.php';

$jsonDir = dirname(__DIR__) . '/post_json';

// --- Handle AJAX POST (save decision) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    echo json_encode(saveDecision($jsonDir, $input));
    exit;
}

// --- Load data and render view ---
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
clearstatcache();

$currentView = $_GET['view'] ?? 'pending';
$data = loadViewData($jsonDir, $currentView);

include __DIR__ . '/includes/templates/layout.php';
