<?php
include 'metricool.php';
include 'services.php';
include 'database/save_post.php';


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
$action = isset($input['action']) ? $input['action'] : '';

$response = [];

switch ($action) {
    case 'post_general':
        $msgTitulo = $input['titulo'] ?? '';
        $msgTexto = $input['texto'] ?? '';
        $msgHashtags = $input['hashtags'] ?? '';
        $msgFotos = $input['fotos'] ?? [];
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarPostGeneral($api, $targetBlogId, $msgTitulo, $msgTexto, $msgHashtags, $msgFotos);
        break;

    case 'post_twitter':
        $twTitulo = $input['titulo'] ?? '';
        $twTexto = $input['texto'] ?? '';
        $twHashtags = $input['hashtags'] ?? '';
        $twFotos = $input['fotos'] ?? [];
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarPostTwitter($api, $targetBlogId, $twTitulo, $twTexto, $twHashtags, $twFotos);
        break;

    case 'youtube_video':
        $vidTitulo = $input['titulo'] ?? '';
        $vidDesc = $input['descripcion'] ?? '';
        $vidUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarYoutubeVideo($api, $targetBlogId, $vidTitulo, $vidDesc, $vidUrl);
        break;

    case 'youtube_short':
        $shortTitulo = $input['titulo'] ?? '';
        $shortDesc = $input['descripcion'] ?? '';
        $shortUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarYoutubeShort($api, $targetBlogId, $shortTitulo, $shortDesc, $shortUrl);
        break;

    case 'tiktok':
        $tikText = $input['texto'] ?? '';
        $tikUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarTikTok($api, $targetBlogId, $tikText, $tikUrl);
        break;

    case 'instagram_reel':
        $reelText = $input['texto'] ?? '';
        $reelUrl = $input['videoUrl'] ?? '';
        $targetBlogId = $input['blogId'] ?? $blogId;

        $response = publicarInstagramReel($api, $targetBlogId, $reelText, $reelUrl);
        break;

    default:
        http_response_code(400);
        $response = ['error' => "Unknown action: '$action'. Valid actions: post_general, post_twitter, youtube_video, youtube_short, tiktok, instagram_reel"];
        break;
}

/**
 * On successful Metricool response → save to MySQL
 * Note: service functions return a string on error (e.g. "Error en Post General: ...")
 * and an array or null on success (API may return empty body)
 */
if (!is_string($response)) {
    // Ensure response is an array for json_encode and db_saved flag
    if (!is_array($response)) {
        $response = ['metricool_status' => 'ok'];
    }
    // Determine platforms based on action
    $platformsMap = [
        'post_general'   => 'facebook,instagram,linkedin,gmb',
        'post_twitter'   => 'twitter,threads',
        'youtube_video'  => 'youtube',
        'youtube_short'  => 'youtube',
        'tiktok'         => 'tiktok',
        'instagram_reel' => 'instagram',
    ];

    // Extract scheduled date from Metricool response if available
    $scheduledDate = '';
    if (isset($response['publicationDate']['dateTime'])) {
        $scheduledDate = $response['publicationDate']['dateTime'];
    }

    $dbData = [
        'action'             => $action,
        'blog_id'            => $input['blogId'] ?? $blogId,
        'row_number'         => $input['row_number'] ?? null,
        'slug'               => $input['slug'] ?? null,
        'titulo'             => $input['titulo'] ?? null,
        'resumen'            => $input['resumen'] ?? null,
        'contenido'          => $input['contenido'] ?? $input['texto'] ?? null,
        'hashtags'           => $input['hashtags'] ?? null,
        'categoria'          => $input['categoria'] ?? null,
        'url_image'          => $input['url_image'] ?? null,
        'promt_idea_image'   => $input['promt_idea_image'] ?? null,
        'fecha_registro'     => $input['fecha_registro'] ?? null,
        'status'             => $input['status'] ?? null,
        'source_file'        => $input['source_file'] ?? null,
        'fotos'              => json_encode($input['fotos'] ?? []),
        'platforms'          => $platformsMap[$action] ?? '',
        'metricool_response' => json_encode($response),
        'scheduled_date'     => $scheduledDate,
    ];

    $saved = savePublishedPost($dbData);
    $response['db_saved'] = $saved;
}

echo json_encode($response);
exit;
