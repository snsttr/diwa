<?php
/**
 * Output a given HTTP Error Code and a Message, then exit the PHP Execution
 * @param int $pCode
 * @param string $pMessage
 */
function error($pCode = 500, $pMessage = 'An Error occured.') {
    if(!headers_sent()) {
        $responseCodes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Authorization Required',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'No code',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Method Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Temporarily Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            510 => 'Not Extended'
        );

        if(!isset($responseCodes[$pCode])) {
            $responseCodes = 500;
        }

        header('HTTP/1.1 500 ' . $responseCodes[$pCode]);
    }
    echo '<h1>Error ' . $pCode . '</h1>';
    echo '<p>' . $pMessage . '</p>';
    exit;
}

/**
 * Find out if user is logged-in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Output a "Forbidden" Error Message
 *
 * @param string $pMessage
 * @param bool $pIncludeContainer
 * @return string
 */
function getForbiddenMessage($pMessage = 'This page is for registered users only. Please login to enter this page.', $pIncludeContainer = true) {
    $msg = '';
    if($pIncludeContainer) {
        $msg .= '<div class="container"><div class="row"><div class="col-lg-12">';
    }
    $msg .= '<div class="alert alert-danger">' . $pMessage . '</div>';
    if($pIncludeContainer) {
        $msg .= '</div></div></div>';
    }
    return $msg;
}

/**
 * Redirect to the given URL
 * @param null $pUrl
 * @param int $pStatusCode
 */
function redirect($pUrl = null, $pStatusCode = 303) {
    if(null === $pUrl) {
        $pUrl = $_SERVER['REQUEST_URI'];
    }
    header('Location: ' . $pUrl, true, $pStatusCode);
    die();
}

/**
 * Map the Upload Error-Code to the corresponding Message
 * @param $pError
 * @return mixed|string
 */
function getUploadError($pError) {
    $errors = array(
        UPLOAD_ERR_OK           => false,
        UPLOAD_ERR_INI_SIZE     => 'Exceeded filesize limit (php.ini)',
        UPLOAD_ERR_FORM_SIZE    => 'Exceeded filesize limit (MAX_FILE_SIZE in Form)',
        UPLOAD_ERR_PARTIAL      => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE      => 'No file sent',
        UPLOAD_ERR_NO_TMP_DIR   => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE   => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION    => 'A PHP extension stopped the file upload'
    );

    if(isset($errors[$pError])) {
        return $errors[$pError];
    }
    else {
        return 'Unspecified error';
    }
}

/**
 * Get the Gliphy-Icon HTML
 *
 * @param $pIcon
 * @param null $pTitle
 * @return string
 */
function icon($pIcon, $pTitle = null) {
    return '<span class="glyphicon glyphicon-' . $pIcon . '"' . (null === $pTitle ? '' : ' title="' . $pTitle . '"') . '></span>';
}

/**
 * Perform a user login
 *
 * @param $pUsername
 * @param $pPassword
 * @return bool
 */
function login($pUsername, $pPassword) {
    global $config, $db, $_SESSION;
    $query = 'SELECT * FROM ' . $config['database']['prefix'] . 'users WHERE username = \'' . $pUsername . '\' AND password = \'' . hash($config['system']['hashing_algorithm'], $pPassword) . '\';';
    $result = $db->query($query);

    // found a matching user?
    if($resultRow = $result->fetchArray()) {
        // delete session data
        session_unset();

        // save user to session
        $_SESSION['user_id'] = $resultRow['id'];

        return true;
    }
    return false;
}