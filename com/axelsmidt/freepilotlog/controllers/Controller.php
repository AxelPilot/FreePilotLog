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

/**
 * Description of Controller
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
abstract class Controller {

    /**
     *
     */
    public function __construct() {
        // Confirmed.
        if (isset($_POST['confirmed'])) {
            $this->confirmed_action();
        }

        // Submitted.
        elseif (isset($_POST['submitted'])) {
            $this->submitted_action();
        } else { // Not yet submitted.
            $this->initial_action();
        }
    }

    /**
     *
     */
    abstract protected function initial_action();

    /**
     *
     */
    abstract protected function submitted_action();

    /**
     *
     */
    abstract protected function confirmed_action();

    /**
     * Returns the subtitle of the current page.
     */
    protected function get_page_subtitle() {
        return isset($this->page_subtitle) ? $this->page_subtitle : NULL;
    }

    /**
     * Sets the subtitle of the current page.
     */
    protected function set_page_subtitle($subtitle) {
        $this->page_subtitle = $subtitle;
    }

    /**
     * Prints the subtitle onto the current page.
     */
    protected function print_page_subtitle() {
        echo '<h1>' . $this->page_subtitle . '</h1>';
    }

}
