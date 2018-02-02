<?php

// load config
try {
    require ROOT_PATH . '/config.php';
    if(false === $config || !is_array($config)) {
        throw new Exception('the $config-variable seems not to be available. Please check your config.php.');
    }
}
catch(Exception $ex) {
    error(500, 'The config could not be loaded: ' . $ex->getMessage());
}

// set error reporting
error_reporting(isset($config['php']['error_reporting']) ? $config['php']['error_reporting'] : 0);

// include function library
try {
    require_once SYSTEM_PATH . '/functions.php';
}
catch(Exception $ex) {
    die('Error: could not include "functions.php"');
}

// establish DB Connection
$db = null;
try {
    if (extension_loaded('PDO') && extension_loaded('pdo_' . $config['database']['driver'])) {
        if('sqlite' === $config['database']['driver']) {
            $db = new PDO($config['database']['driver'] . ':' . $config['database']['database'],
                null,
                null,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        elseif(in_array($config['database']['driver'], array('mysql', 'pgsql'), true)) {
            $db = new PDO($config['database']['driver'] . ':host=' . $config['database']['server'] . ';dbname=' . $config['database']['database'],
                $config['database']['username'],
                $config['database']['password'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
    }
}
catch(Exception $ex) {
    error(500, 'The Database-Connection could not be established (Driver: ' . $config['database']['driver'] . ')', $ex);
}
// connection available?
if(null === $db) {
    error(500, 'The Database-Connection could not be established (Driver: ' . $config['database']['driver'] . ')');
}

// include Model
try {
    require_once SYSTEM_PATH . '/model.php';
    $model = new Model($db, $config['database']['prefix']);
}
catch(Exception $ex) {
    die('Error: could not include "model.php"');
}

// include Session Management
try {
    require_once SYSTEM_PATH . '/session.php';
}
catch(Exception $ex) {
    error(500, 'The session lib could not be found: ' . $ex->getMessage());
}

// include parsedown extension if available (optional)
$parsedownInclude = ROOT_PATH . '/vendor/erusev/parsedown/Parsedown.php';
if(file_exists($parsedownInclude)) {
    include $parsedownInclude;
}

