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
 *
 */
class Register_User extends Controller {

    public function __construct() {
        $user = new models\User($_POST['email'], $_POST['password1'],
                $_POST['password2'], $_POST['lastname'], $_POST['firstname']);

        $user->save_to_db();

        // If successful, redirect to index.php and display a confirmation message.
        header("Location: index.php?msg=Thank you for registering! A confirmation email has been sent to your email address.<br />Please click the link in the email to activate your account.");
        exit(); // Quit the script.
    }

}
