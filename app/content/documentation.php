<?php

// get content of directory
try {
    $rootPath = ROOT_PATH . '/../docs/';
    $path = './';
    if (isset($_GET['path']) && !empty($_GET['path']) && file_exists($rootPath . $_GET['path'])) {
        $path = $_GET['path'];
    }

    // make sure last char is a /
    if($path[strlen($path) - 1] !== '/') {
        $path .= '/';
    }

    $globList = glob($rootPath . $path . '*');
}
catch(Exception $ex) {
    error(500, 'Could not get content of directory', $ex);
}

?>

<div class="row">
    <div class="col-lg-3">
        <h1>Documentation</h1><hr/>
        <ul class="list-group">
            <?php
            if($path !== './') {
                echo '<li class="list-group-item">' . icon('folder-close') . ' <strong><a href="?page=documentation&path=' . dirname($path) . '/">..</a></strong></li>';
            }
            $files = array();
            // output directories
            foreach ($globList as $item) {
                if(is_dir($item)) {
                    echo '<li class="list-group-item">' . icon('folder-close') . ' <strong><a href="?page=documentation&path=' . $path . basename($item) . '/">' . basename($item) . '</a></strong></li>';
                }
                else {
                    $files[] = $item;
                }
            }

            // output files
            foreach ($files as $file) {
                echo '<li class="list-group-item">' . icon('file') . ' <strong><a href="?page=documentation&path=' . $path .'&file=' . basename($file) . '">' . basename($file) . '</a></strong></li>';
            }
            ?>
        </ul>
    </div>
    <div class="col-lg-9">
        <?php
        if(isset($_GET['file']) && !empty($_GET['file'])) {
            echo '<h1>' . $_GET['file'] . '</h1><hr/>';
            if (file_exists($rootPath . $path . $_GET['file'])) {
                $fileExtension = strtolower(substr($_GET['file'], strrpos($_GET['file'], '.') + 1));
                $validMarkdownExtensions = array('md', 'markdown');
                $validExtensions = array_merge(array('txt', 'text', 'md', 'markdown', 'asciidoc', 'adoc'), $validMarkdownExtensions);
                if (in_array($fileExtension, $validExtensions)) {
                    if (512 * 1024 > filesize($rootPath . $path . $_GET['file'])) {
                        $fileContent = file_get_contents($rootPath . $path . $_GET['file']);
                        // try to parse markdown-files
                        $markdown = false;
                        if (in_array($fileExtension, $validMarkdownExtensions)) {
                            if (class_exists('Parsedown')) {
                                try {
                                    $parsedown = new Parsedown();
                                    echo $parsedown->text($fileContent);
                                    $markdown = true;
                                } catch (Exception $ex) {
                                    // leave empty
                                }
                            }
                        }
                        // dont parse markdown
                        if (!$markdown) {
                            echo '<pre>' . $fileContent . '</pre>';
                        }
                    } else {
                        echo '<div class="alert alert-info">The file ' . $_GET['file'] . ' is too big (512KB max).</div>';
                    }
                } else {
                    echo '<div class="alert alert-info">You can only view files with the following file extensions: ' . implode(', ', $validExtensions) . '</div>';
                }
            } else {
                echo '<div class="alert alert-info">The file ' . $_GET['file'] . ' does not exist.</div>';
            }
        }
        ?>
    </div>
</div>