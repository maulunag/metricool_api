<?php
/**
 * SAVE PUBLISHED POST TO MySQL
 */

require_once __DIR__ . '/db_config.php';

/**
 * Save a successfully published post to the database.
 *
 * @param array $data  Associative array with post data:
 *   - action, blog_id, row_number, slug, titulo, resumen, contenido,
 *     hashtags, categoria, url_image, promt_idea_image, fecha_registro,
 *     status, source_file, fotos, platforms, metricool_response, scheduled_date
 * @return bool
 */
function savePublishedPost(array $data): bool {
    try {
        $pdo = getDbConnection();

        $sql = "INSERT INTO `metricool_posts` 
                (`action`, `blog_id`, `row_number`, `slug`, `titulo`, `resumen`, `contenido`,
                 `hashtags`, `categoria`, `url_image`, `promt_idea_image`, `fecha_registro`,
                 `status`, `source_file`, `fotos`, `platforms`, `metricool_response`, `scheduled_date`)
                VALUES 
                (:action, :blog_id, :row_number, :slug, :titulo, :resumen, :contenido,
                 :hashtags, :categoria, :url_image, :promt_idea_image, :fecha_registro,
                 :status, :source_file, :fotos, :platforms, :metricool_response, :scheduled_date)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':action'             => $data['action'] ?? '',
            ':blog_id'            => $data['blog_id'] ?? 0,
            ':row_number'         => $data['row_number'] ?? null,
            ':slug'               => $data['slug'] ?? null,
            ':titulo'             => $data['titulo'] ?? null,
            ':resumen'            => $data['resumen'] ?? null,
            ':contenido'          => $data['contenido'] ?? null,
            ':hashtags'           => $data['hashtags'] ?? null,
            ':categoria'          => $data['categoria'] ?? null,
            ':url_image'          => $data['url_image'] ?? null,
            ':promt_idea_image'   => $data['promt_idea_image'] ?? null,
            ':fecha_registro'     => $data['fecha_registro'] ?? null,
            ':status'             => $data['status'] ?? null,
            ':source_file'        => $data['source_file'] ?? null,
            ':fotos'              => $data['fotos'] ?? null,
            ':platforms'          => $data['platforms'] ?? null,
            ':metricool_response' => $data['metricool_response'] ?? null,
            ':scheduled_date'     => $data['scheduled_date'] ?? null,
        ]);

        return true;
    } catch (PDOException $e) {
        error_log('savePublishedPost error: ' . $e->getMessage());
        return false;
    }
}
