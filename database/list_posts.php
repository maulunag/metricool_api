<?php
/**
 * LIST ALL PUBLISHED POSTS FROM MySQL
 */

require_once __DIR__ . '/db_config.php';

header('Content-Type: application/json');

try {
    $pdo = getDbConnection();

    $stmt = $pdo->query("SELECT * FROM metricool_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();

    echo json_encode([
        'total' => count($posts),
        'posts' => $posts
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
