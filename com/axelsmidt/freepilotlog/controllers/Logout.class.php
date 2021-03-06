<?php

/*
 * Copyright (C) 2020 Axel Smidt <http://AxelSmidt.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace com\axelsmidt\freepilotlog\controllers;

class Logout {

    public function __construct() {
        if (is_loggedIn()) {
            // Log out the user.
            $_SESSION = array(); // Destroy the session variables.
            session_destroy(); // Destroy the session itself.
            setcookie(session_name(), '', time() - 300, '/', '', 0); // Destroy the cookie associated with the session.

            ob_end_clean(); // Delete the buffer.
            header("Location: index.php"); // Redirect to index.php.
            exit(); // Quit the script.
        }
    }

}
