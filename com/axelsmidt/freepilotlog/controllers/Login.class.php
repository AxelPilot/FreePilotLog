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

use com\axelsmidt\aslib as aslib;
use com\axelsmidt\freepilotlog\models as models;
use com\axelsmidt\freepilotlog\views as views;

/**
 * Description of Login
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
class Login {
    public function __construct() {
        try {
            $user = new models\User(NULL, $_POST['email'], $_POST['password']);
            $admin_activation_code = $user->get_admin_activation_code();

            $_SESSION['user_ID'] = $user->get_user_ID();
            $_SESSION['firstname'] = $user->get_firstname();
            $_SESSION['lastname'] = $user->get_lastname();
            $_SESSION['admin'] = $user->is_admin() ? "Y" : ( isset($admin_activation_code) ? "applied" : "N" );

            ob_end_clean(); // Delete the buffer.
            header( "Location: " . "index.php" );
            exit(); // Quit the script.
        } catch (aslib\DbErrorException $e) {
            ?>
            <div class="Error"><?php echo $e->getArrayMessage(); ?></div>
            <p><div class="Error">A technincal error occured. Please try again later.</div></p>
            <?php
        } catch (aslib\DbException $e) {
            ?>
            <p><div class="Error"><?php echo $e->getArrayMessage(); ?></div></p>
            <?php
        } catch (aslib\FormValidationException $e) {
            $this->validation_exceptions = $e->getArrayMessage();
        }
    ?>
    <h1>Sign Into Your Account</h1>
    <?php
    
    new views\Login_Form('index.php', $this->validation_exceptions);
    }
}
