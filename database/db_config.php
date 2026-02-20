<?php
/**
 * DATABASE CONFIGURATION
 * MySQL connection via PDO
 */

define('DB_HOST', 'localhost');       // ← Cambia por tu host
define('DB_NAME', 'tampatek_metricool_db');   // ← Cambia por tu base de datos
define('DB_USER', 'tampatek_metricool_user');           // ← Cambia por tu usuario
define('DB_PASS', '..tampateks--');               // ← Cambia por tu password
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO database connection
 */
function getDbConnection(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}
