<?php

namespace com\axelsmidt\freepilotlog\controllers;

class Logout {

    public function __construct() {
        echo 'DETTE ER EN TEST';
        if (is_loggedIn()) {
            // Log out the user.
            $_SESSION = array(); // Destroy the session variables.
            session_destroy(); // Destroy the session itself.
            setcookie(session_name(), '', time() - 300, '/', '', 0); // Destroy the cookie associated with the session.
        }
    }

}

?>