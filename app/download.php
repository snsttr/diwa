<?php
try {
    $file = __DIR__ . '/files/' . $_GET['file'];

    if (!file_exists($file)) {
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    if(function_exists('mime_content_type')) {
        $mimeType = mime_content_type($file);
    }
    else {

        $mimeTypes = array(
            'txt' => 'text/plain',
            'md' => 'text/plain',
            'ascii' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'phtml' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'sh' => 'application/x-sh',
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'cab' => 'application/vnd.ms-cab-compressed',
            'gz' => ' application/gzip ',
            'tar' => 'application/x-tar',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument. wordprocessingml.documen',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument. spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            'aiff' => 'audio/aiff',
            'asp' => 'text/asp',
            'asx' => 'application/x-mplayer2',
            'au' => 'audio/basic',
            'avi' => 'video/avi',
            'bsh' => 'application/x-bsh',
            'bz' => 'application/x-bzip',
            'bz2' => 'application/x-bzip2',
            'class' => 'application/java',
            'com' => 'application/octet-stream',
            'csh' => 'application/x-csh',
            'dot' => 'application/msword',
            'gzip' => 'application/x-gzip',
            'java' => 'text/plain',
            'midi' => 'audio/midi',
            'mp2' => 'audio/mpeg',
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4',
            'mpa' => 'audio/mpeg',
            'mpeg' => 'video/mpeg',
            'mpg' => 'audio/mpeg',
            'py' => 'text/x-script.phyton',
            'tgz' => 'application/gnutar',
            'wav' => 'audio/wav',
        );
        $fileExtension = substr($file, strrpos($file, '.') + 1);
        if(false !== $fileExtension && !empty($fileExtension) && isset($mimeTypes[$fileExtension])) {
            $mimeType = $mimeTypes[$fileExtension];
        } else {
            $mimeType = 'application/octet-stream';
        }
    }

    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    readfile($file);
}
catch(Exception $ex) {
    header('HTTP/1.1 500  Internal Server Error');
    exit;
}