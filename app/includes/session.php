<?php
// start session
session_start();

// get user information
try {
    if(!$installation && isset($_SESSION['user_id'])) {
        $result = $model->getUserData($_SESSION['user_id']);

        // found a matching user?
        if (false !== $result && 0 < count($result)) {
            // save user to session
            $_SESSION['user'] = $result[0];
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
    error(500, 'Error in Session Management', $ex);
}