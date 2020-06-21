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

use com\axelsmidt\freepilotlog\models as models;
use com\axelsmidt\freepilotlog\controllers as controllers;

/**
 * Description of View
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
class View {

    public function __construct() {
        ?>

    <body<?php echo isset($_GET['onload']) ? ' onload="' . $_GET['onload'] . '"' : ''; ?>>
        <div class="container">
            <div id="Main">
                <div id="Content">
        <?php
    }

}
                