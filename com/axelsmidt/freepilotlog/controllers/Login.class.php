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

use com\axelsmidt\freepilotlog\models as models;

/**
 * Signs the user into a user account based on the entered
 * email address and password.
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
class Login extends Controller {

    public function __construct() {
        // Sending login request to the model (models\User).
        $user = new models\User($_POST['email'], $_POST['password']);
        
        // If login was successful, create session variables with user data.
        $_SESSION['email'] = $user->get_email();
        $_SESSION['firstname'] = $user->get_firstname();
        $_SESSION['lastname'] = $user->get_lastname();

        ob_end_clean(); // Delete the buffer.
        header("Location: index.php"); // Redirect to index.php.
        exit(); // Quit the script.
    }

}
