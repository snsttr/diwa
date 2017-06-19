<?php
// start session
session_start();

// get user information
try {
    if(isset($_SESSION['user_id'])) {
        $query = 'SELECT * FROM ' . $config['database']['prefix'] . 'users WHERE id = ' . $_SESSION['user_id'];
        $result = $db->query($query);

        // found a matching user?
        if ($resultRow = $result->fetchArray()) {
            // save user to session
            $_SESSION['user'] = $resultRow;
        } else {
            // delete all session data
            session_unset();
        }
    } else {
        // delete all session data
        session_unset();
    }
}
catch(Exception $ex) {
    error(500, 'Error in Session Management: ' . $ex->getMessage());
}