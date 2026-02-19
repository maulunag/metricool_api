<?php
include 'metricool.php';
include 'services.php';



// --- ROUTER LOGIC ---

// Ensure we return JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST.']);
    exit;
}

// Get the raw POST data
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

/**
 * Servicios esperados (actions):
 */
$validServices = [
    'post_general',
    'post_twitter',
    'youtube_video',
    'youtube_short',
    'tiktok',
    'instagram_reel'
];

// Determine the action (service) to perform
// Expecting 'action' in JSON body, e.g., { "action": "post_general", ... }
$action = isset($input['action']) ? $input['action'] : '';

$response = [];

switch ($action) {
    case 'post_general':
        // Required fields: titulo, texto, hashtags, fotos
        // Optional: blogId (defaults to $blogId global)
        $msgTitulo = $input['titulo'] ?? '';
        $msgTexto = $input['texto'] ?? '';
        $msgHashtags = $input['hashtags'] ?? '';
        $msgFotos = $input['fotos'] ?? [];
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarPostGeneral($api, $targetBlogId, $msgTitulo, $msgTexto, $msgHashtags, $msgFotos);
        break;

    case 'youtube_video':
        // Required: titulo, descripcion, videoUrl
        $vidTitulo = $input['titulo'] ?? '';
        $vidDesc = $input['descripcion'] ?? '';
        $vidUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarYoutubeVideo($api, $targetBlogId, $vidTitulo, $vidDesc, $vidUrl);
        break;

    case 'youtube_short':
        // Required: titulo, descripcion, videoUrl
        $shortTitulo = $input['titulo'] ?? '';
        $shortDesc = $input['descripcion'] ?? '';
        $shortUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarYoutubeShort($api, $targetBlogId, $shortTitulo, $shortDesc, $shortUrl);
        break;

    case 'tiktok':
        // Required: texto, videoUrl
        $tikText = $input['texto'] ?? '';
        $tikUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarTikTok($api, $targetBlogId, $tikText, $tikUrl);
        break;

    case 'instagram_reel':
        // Required: texto, videoUrl
        $reelText = $input['texto'] ?? '';
        $reelUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarInstagramReel($api, $targetBlogId, $reelText, $reelUrl);
        break;

    case 'post_twitter':
        // Required fields: titulo, texto, hashtags, fotos
        $twTitulo = $input['titulo'] ?? '';
        $twTexto = $input['texto'] ?? '';
        $twHashtags = $input['hashtags'] ?? '';
        $twFotos = $input['fotos'] ?? [];
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarPostTwitter($api, $targetBlogId, $twTitulo, $twTexto, $twHashtags, $twFotos);
        break;

    default:
        http_response_code(400);
        $response = ['error' => "Unknown action: '$action'. Valid actions: post_general, post_twitter, youtube_video, youtube_short, tiktok, instagram_reel"];
        break;
}

echo json_encode($response);
exit;


