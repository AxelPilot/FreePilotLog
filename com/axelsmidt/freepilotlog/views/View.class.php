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

/**
 * Description of View
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
abstract class View {

    protected $user;
    protected $heading;
    protected $validation_exceptions;

   /**
     *
     */
    public function __construct() {
        // Displays the heading of the current page.
        $this->show_heading(); 
        
        // Form data confirmed action.
        if (filter_input(INPUT_POST, 'confirmed')) {
            $this->confirmed_action();
        }

        // Form data submitted action.
        elseif (filter_input(INPUT_POST, 'submitted')) {
            $this->submitted_action();
        }

        // Initial (form not yet submitted) action.
        else {
            $this->initial_action();
        }
    }

    /**
     * Initial action to be performed (when form data is not yet submitted).
     */
    abstract protected function initial_action();

    /**
     * Action to be performed when form data is submitted.
     */
    abstract protected function submitted_action();

    /**
     * Action to be performed when form data is confirmed.
     */
    abstract protected function confirmed_action();

    /**
     * Returns the heading of the current page.
     */
    protected function get_heading() {
        return isset($this->heading) ? $this->heading : NULL;
    }

    /**
     * Sets the heading of the current page.
     */
    protected function set_heading($heading) {
        $this->heading = $heading;
    }

    /**
     * Displays the heading of the current page.
     */
    protected function show_heading() {
        ?><h1><?php echo $this->heading; ?></h1><?php
    }

}
                