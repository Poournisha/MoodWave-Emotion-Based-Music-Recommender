<?php
// This file is a placeholder for OAuth callbacks
// Since we don't use external APIs, it's not needed for local data

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'message' => 'Callback endpoint',
        'status' => 'No external API integration required'
    ]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>