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
 * Description of MainMenu
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
class MainMenu {

    protected $loggedIn;

    public function __construct($loggedIn) {
        $this->loggedIn = $loggedIn;
        ?>
        <nav class="mainmenu"> 
            <ul class="nav">
                <?php
                if ($this->loggedIn) {
                        ?>

                <li><a href="addflight.php"><b>+ ADD FLIGHT</b></a></li>

                    <li><a href="#">LOGBOOK</a>
                    <ul class="nav navleft">
                         <li><a href="option1.php">OPTION 1</a></li>
                         <li><a href="option2.php">OPTION 2</a></li>
                         <li><a href="option3.php">OPTION 3</a></li>
                         <li><a href="option4.php">OPTION 4</a></li>
                     </ul>
                     </li>

                    <li><a href="#">AIRCRAFT</a>
                    <ul class="nav navleft">
                         <li><a href="option1.php">OPTION 1</a></li>
                         <li><a href="option2.php">OPTION 2</a></li>
                         <li><a href="option3.php">OPTION 3</a></li>
                         <li><a href="option4.php">OPTION 4</a></li>
                     </ul>
                     </li>

                    <li><a href="#">PROFILE</a>
                        <ul class="nav navright">
                            <li><a href="change_password.php">CHANGE PASSWORD</a></li>
                            <li><a href="logout.php">LOG OUT</a></li>
                        </ul>
                    </li>
                    <?php
                } else {
                    ?>

                    <li><a href="login.php">SIGN IN</a></li>
                    <li><a href="register_user.php">CREATE ACCOUNT</a></li>
                    <?php
                }
                ?>

            </ul>
        </nav>
        <?php
    }

}
