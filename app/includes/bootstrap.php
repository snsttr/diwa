<?php

// load config
try {
    @require __DIR__ . '/../config.php';
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
    require_once __DIR__ . '/functions.php';
}
catch(Exception $ex) {
    die('Error: could not include "functions.php"');
}

// Is sqlite3 Available?
if(!class_exists('SQLite3')) {
    error(500, 'Make sure that you have loaded the sqlite3 extension for PHP. See https://secure.php.net/manual/en/sqlite3.installation.php');
}

// establish DB Connection
try {
    $db = new SQLite3(__DIR__ . '/../../database/' . $config['database']['database']);
    $db->enableExceptions(true);
    // TODO check if DB Connection could be established
}
catch(Exception $ex) {
    error(500, 'The Connection to the Database could not be established: ' . $ex->getMessage());
}

// include Session Management
try {
    require_once __DIR__ . '/session.php';
}
catch(Exception $ex) {
    error(500, 'The session lib could not be found: ' . $ex->getMessage());
}

// include parsedown extension if available (optional)
$parsedownInclude = __DIR__ . '/../vendor/erusev/parsedown/Parsedown.php';
if(file_exists($parsedownInclude)) {
    include $parsedownInclude;
}