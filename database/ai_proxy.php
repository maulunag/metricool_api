<?php
/**
 * GEMINI AI PROXY
 * Securely proxies AI requests to Gemini API to protect the API key.
 */

header('Content-Type: application/json');

// 1. Load Environment Variables from .env
$envPath = dirname(__DIR__) . '/.env';
if (!file_exists($envPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Environment file not found.']);
    exit;
}

$env = parse_ini_file($envPath);
$apiKey = $env['GEMINI_API_KEY'] ?? '';
$model = $env['GEMINI_MODEL'] ?? 'gemini-2.0-flash';

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'API Key not configured.']);
    exit;
}

// 2. Get Frontend Payload
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['contents'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request payload.']);
    exit;
}

// 3. Make Secure Request to Gemini
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// 4. Handle Result
if ($curlError) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'CURL Error: ' . $curlError]);
    exit;
}

http_response_code($httpCode);
echo $response;
