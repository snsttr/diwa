<?php

$requirementsErrors = array();

// load config
try {
    if(!include(ROOT_PATH . '/config.php')) {
        $requirementsErrors[] = 'The config.php could not be loaded.';
    }
    else {
        if (false === $config || !is_array($config)) {
            $requirementsErrors[] = 'The $config-variable seems not to be available. Please check your config.php.';
        }
    }
}
catch(Exception $ex) {
    $requirementsErrors[] = 'The config.php could not be loaded: ' . $ex->getMessage();
}

// set error reporting
error_reporting(isset($config['php']['error_reporting']) ? $config['php']['error_reporting'] : 0);

// include function library
try {
    if(!include(SYSTEM_PATH . '/functions.php')) {
        die('The functions.php could not be loaded.');
    }
}
catch(Exception $ex) {
    die('The functions.php could not be loaded: ' . $ex->getMessage());
}

// are we running on heroku?
$herokuError = false;
if(getenv('HEROKU') || getenv('HEROKU_APP_DIR')) {
    // is cleardb available?
    if (false !== getenv('CLEARDB_DATABASE_URL')) {
        // instead of using database settings from config.php, use CLEARDB
        $url = parse_url(getenv('CLEARDB_DATABASE_URL'));
        $config['database']['driver'] = 'mysql';
        $config['database']['host'] = $url['host'];
        $config['database']['username'] = $url['user'];
        $config['database']['password'] = $url['pass'];
        $config['database']['database'] = substr($url['path'], 1);
        define('HEROKU', 1);
    }
    else {
        $requirementsErrors[] = 'It seems that you are trying to run DIWA on Heroku. Please install CLEARDB Addon (free Plan is sufficient) for the app yourself.';
        $herokuError = true;
    }
}

if(!$herokuError) {
// establish DB Connection
// currently only sqlite & mysql are supported
    $db = null;
    try {
        if (extension_loaded('PDO') && extension_loaded('pdo_' . $config['database']['driver'])) {
            if ('sqlite' === $config['database']['driver']) {
                $db = new PDO($config['database']['driver'] . ':' . $config['database']['database'],
                    null,
                    null,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } elseif ('mysql' === $config['database']['driver']) {
                $connectionString = $config['database']['driver'] . ':host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'];
                $db = new PDO($connectionString,
                    $config['database']['username'],
                    $config['database']['password'],
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } else {
                $requirementsErrors[] = 'The configured PDO Driver "' . $config['database']['driver'] . '" is not a valid option. Please change the corresponding setting in your config.php';
            }
        } else {
            $requirementsErrors[] = 'The configured PDO Driver "' . $config['database']['driver'] . '" could not be found. Please make sure it is loaded in your php.ini or change the corresponding setting in your config.php';
        }
    } catch (Exception $ex) {
        $requirementsErrors[] = 'The connection to the configured database could not be established: ' . $ex->getMessage();
    }
}

// reset or install system?
try {
    if(!include(INSTALLATION_PATH . '/check.php')) {
        die('Error: could not include "check.php".');
    }
}
catch(Exception $ex) {
    die('Error: could not include "check.php": ' . $ex->getMessage());
}

// include Model
try {
    if(!include(SYSTEM_PATH . '/model.php')) {
        die('Error: could not include "model.php".');
    }
    $model = new Model($db, $config['database']['prefix']);
}
catch(Exception $ex) {
    die('Error: could not include "model.php": ' . $ex->getMessage());
}

// include Session Management
try {
    if(!include(SYSTEM_PATH . '/session.php')) {
        die('Error: could not include "session.php".');
    }
}
catch(Exception $ex) {
    die('Error: could not include "session.php: ' . $ex->getMessage());
}

// include parsedown extension if available (optional)
$parsedownInclude = ROOT_PATH . '/vendor/erusev/parsedown/Parsedown.php';
if(file_exists($parsedownInclude)) {
    include $parsedownInclude;
}
