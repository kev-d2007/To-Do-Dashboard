<?php
$src = __DIR__ . '/img/favicon.ico';
$dest = __DIR__ . '/favicon.ico';
if (!file_exists($src)) {
    http_response_code(404);
    echo 'Source favicon not found.';
    exit;
}
if (copy($src, $dest)) {
    echo 'Copied favicon to project root as favicon.ico';
} else {
    http_response_code(500);
    echo 'Failed to copy favicon.';
}
