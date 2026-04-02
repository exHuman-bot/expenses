<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$VALID_TOKEN = 'fox'; // ← такой же как в index.html
$file = __DIR__ . '/data.json';

// Проверяем токен
$token = $_GET['token'] ?? '';
if ($token !== $VALID_TOKEN) {
    http_response_code(403);
    echo '{"error": "forbidden"}';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_exists($file) ? file_get_contents($file) : '[]';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    if (json_decode($data) === null) {
        http_response_code(400);
        echo '{"error": "invalid json"}';
        exit;
    }
    file_put_contents($file, $data);
    echo '{"ok": true}';
    exit;
}
