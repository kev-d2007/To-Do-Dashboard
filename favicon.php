<?php
// Serve favicon from img/ folder so browsers can load it via /favicon.php
$path = __DIR__ . '/img/favicon.ico';
if (!file_exists($path)) {
    http_response_code(404);
    exit;
}
header('Content-Type: image/x-icon');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
