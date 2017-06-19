<?php

// bootstrap DIWA
require_once __DIR__ . '/includes/bootstrap.php';

// include header
require_once __DIR__ . '/layout/header.php';

// include content
if(isset($_GET['page'])) {
    $content = $_GET['page'];
}
else {
    $content = 'index';
}

$contentFile = __DIR__ . '/content/' . $content . '.php';

if(file_exists($contentFile)) {
    require_once $contentFile;
}
else {
    require_once __DIR__ . '/content/404.php';
}

// include footer
require_once __DIR__ . '/layout/footer.php';

// close db Connection
@$db->close();