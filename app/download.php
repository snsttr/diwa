<?php
try {
    $file = __DIR__ . '/files/' . $_GET['file'];

    if (!file_exists($file)) {
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    $mimeType = mime_content_type($file);

    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    readfile($file);
}
catch(Exception $ex) {
    header('HTTP/1.1 500  Internal Server Error');
    exit;
}