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

namespace com\axelsmidt\freepilotlog\views;

use com\axelsmidt\aslib as aslib;
use com\axelsmidt\freepilotlog\controllers as controllers;

/**
 *
 */
class Register_User extends View {

    protected $user;
    protected $heading = 'Create User Account';
    protected $validation_exceptions;

    /**
     * User registration form.
     */
    protected function initial_action() {
        new Register_User_Form($this->validation_exceptions);
    }

    /**
     * Save the user to the database. If unsuccessful,
     * catch exceptions and return to registration form.
     */
    protected function submitted_action() {
        try {
            new controllers\Register_User();
        } catch (aslib\DbErrorException $e) {
            ?><div class="Error"><?php echo $e->getArrayMessage(); ?></div>
            <p><div class="Error">A technical error has occured. Please try again later.</div></p><?php
        } catch (aslib\DbException $e) {
            ?><div class="Error"><?php echo $e->getArrayMessage(); ?></div>
            <p><div class="Error">A technical error has occured. Please try again.</div></p><?php
        } catch (aslib\FormValidationException $e) {
            $this->validation_exceptions = $e->getArrayMessage();
        }

        new Register_User_Form($this->validation_exceptions);
    }

    /**
     * Not used.
     */
    protected function confirmed_action() {
        
    }

}
