<?php

define('ROOT_PATH', __DIR__);
define('SYSTEM_PATH', ROOT_PATH . '/includes');
define('CONTENT_PATH', ROOT_PATH . '/content');
define('LAYOUT_PATH', ROOT_PATH . '/layout');

// redirect to setup?
if(!file_exists(ROOT_PATH . '/config.php')) {
    // TODO: Redirect to install.php
}

// bootstrap DIWA
require_once SYSTEM_PATH . '/bootstrap.php';

// start output buffering
ob_start();

// include header
require_once LAYOUT_PATH . '/header.php';

// include content
if(isset($_GET['page'])) {
    $content = $_GET['page'];
}
else {
    $content = 'index';
}

$contentFile = CONTENT_PATH . '/' . $content . '.php';

if(file_exists($contentFile)) {
    require_once $contentFile;
}
else {
    require_once CONTENT_PATH . '/404.php';
}

// include footer
require_once LAYOUT_PATH . '/footer.php';

// cleanup: Send content & close DB
ob_end_flush();
// TODO: @$db->close();