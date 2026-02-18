<?php
/**
 * SERVICIO DE PUBLICACIÓN EN METRICOOL
 */

$api = new MetricoolPoster();
$blogId = 5820853; // TU ID REAL AQUÍ

/**
 * Helper para generar una fecha de publicación aleatoria entre 4 y 24 horas
 */
function generarFechaAleatoria() {
    // dia 24 horas
    // semana 168 horas
    // mes 720 horas
    // año 8760 horas

    $horasAleatorias = rand(24, 168);
    return date('Y-m-d\TH:i:s', strtotime("+$horasAleatorias hours"));
}

/**
 * 1. PUBLICACIÓN GENERAL (Facebook, Instagram, LinkedIn)
 */
function publicarPostGeneral($api, $blogId, $titulo, $texto, $hashtags, $fotos) {
    try {
        $fecha = generarFechaAleatoria();
        // echo "Programando Post General para: $fecha\n";
        
        return $api->createPost(
            $blogId,
            $titulo,
            $texto,
            $hashtags,
            $fotos,
            $fecha
        );
    } catch (Exception $e) {
        return "Error en Post General: " . $e->getMessage();
    }
}

/**
 * 2. YOUTUBE VIDEO
 */
function publicarYoutubeVideo($api, $blogId, $titulo, $descripcion, $videoUrl) {
    try {
        $fecha = generarFechaAleatoria();
        // echo "Programando YouTube Video para: $fecha\n";
        
        return $api->createYoutubeVideo(
            $blogId,
            $titulo,
            $descripcion,
            $videoUrl,
            $fecha
        );
    } catch (Exception $e) {
        return "Error en YouTube Video: " . $e->getMessage();
    }
}

/**
 * 3. YOUTUBE SHORTS
 */
function publicarYoutubeShort($api, $blogId, $titulo, $descripcion, $videoUrl) {
    try {
        $fecha = generarFechaAleatoria();
        //echo "Programando YouTube Short para: $fecha\n";
        
        return $api->createYoutubeShorts(
            $blogId,
            $titulo,
            $descripcion,
            $videoUrl,
            $fecha
        );
    } catch (Exception $e) {
        return "Error en YouTube Short: " . $e->getMessage();
    }
}

/**
 * 4. TIKTOK
 */
function publicarTikTok($api, $blogId, $text, $videoUrl) {
    try {
        $fecha = generarFechaAleatoria();
        //echo "Programando TikTok para: $fecha\n";
        
        return $api->createTikTokPost(
            $blogId,
            $text,
            $videoUrl,
            $fecha
        );
    } catch (Exception $e) {
        return "Error en TikTok: " . $e->getMessage();
    }
}

/**
 * 5. INSTAGRAM REEL
 */
function publicarInstagramReel($api, $blogId, $text, $videoUrl) {
    try {
        $fecha = generarFechaAleatoria();
        // echo "Programando Instagram Reel para: $fecha\n";
        
        return $api->createInstagramReel(
            $blogId,
            $text,
            $videoUrl,
            $fecha
        );
    } catch (Exception $e) {
        return "Error en Instagram Reel: " . $e->getMessage();
    }
}
