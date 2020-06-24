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
 * Description of Footer
 *
 * @author Axel Smidt <http://AxelSmidt.com>
 */
class Footer {

    public function __construct() {
        ?>

        </div> <!-- End of class "Content" -->
        </div> <!-- End of class "Main" -->
        <!-- End of changeable content -->

        <div id="Header">
            <header class="wrapper">
                <!-- Display logo on top menubar -->
                <div class="logo"><a href="index.php"><img src="./images/logo.png" alt="Home" name="Insert_logo" id="Insert_logo" /></a></div>

                <?php
                // Display username (if logged in) on top menubar.
                $loggedIn = ( isset($_SESSION['email']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && ( substr($_SERVER['PHP_SELF'], -10) != 'logout.php' ) );
                echo isset($_SESSION['email']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname']) ?
                        "<div class=\"header_title\">- " . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "</div>" : 
                        "<div class=\"header_title\"></div>";

                new MainMenu($loggedIn);
                ?>

            </header>
        </div>

        <div class="clearfloat">
        </div>
        </div> <!-- End of class "container" -->
        <div id="fade_background" style="position:fixed; display:none; top:0; width:100%; height:100%; background:rgba(50,50,50,0.3); z-index:9998;">
        </div>
        </body>
        </html>
        <?php
        // Flush the buffered output.
        ob_end_flush();
    }

}
?>
