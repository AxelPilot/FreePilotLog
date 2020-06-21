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
                    <div class="logo"><a href="index.php"><img src="./images/logo.png" alt="Home" name="Insert_logo" id="Insert_logo" /></a></div>

                    <?php
                    echo isset($_SESSION['user_ID']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname']) ?
                            "<div class=\"header_title\">- " . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "</div>" : "";
                    ?>
                    
                    <nav class="mainmenu"> 
                        <ul class="nav">

                            <?php
                            $loggedIn = ( isset($_SESSION['user_ID']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['admin']) && ( substr($_SERVER['PHP_SELF'], -10) != 'logout.php' ) );

                            if ($loggedIn) {
                                if ($_SESSION['admin'] == "Y") {
                                    ?>
                            
                                    <li><a href="#">ADMIN</a>
                                        <ul class="nav navleft">
                                            <li><a href="register_event.php">REGISTER EXERCISE</a></li>
                                            <li><a href="update_event.php">UPDATE EXERCISE</a></li>
                                            <li><a href="delete_event.php">DELETE EXERCISE</a></li>
                                            <li><a href="register_competitor.php">REGISTER CONTESTANT</a></li>
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>

                                <li><a href="purchase_ticket.php">BUY TICKETS</a></li>

                                <li><a href="#">MY ACCOUNT</a>
                                    <ul class="nav navright">
                                        <?php
                                        if ($_SESSION['admin'] == "N") {
                                            ?>
                                            <li><a href="apply_for_admin.php">APPLY FOR ADMIN</a></li>
                                            <?php
                                        }
                                        ?>

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
                </header>
            </div>

            <div id="RightBar">
                <?php
                if ($loggedIn) {
                    $notification_list = new Notification_List($_SESSION['user_ID']);
                    $notification_list->show();
                }
                ?>
                
            </div>
            <div class="clearfloat">
            </div>
        </div> <!-- End of class "container" -->
        <div id="fade_background" style="position:fixed; display:none; top:0; width:100%; height:100%; background:rgba(50,50,50,0.3); z-index:9998;">
        </div>

        <div id="notification_lightbox" style="position:absolute; display:none; left:50%; top:42%; max-width:40%; background:rgba(255,255,255,0); border:none; z-index:9999;">
            <div align="left" style="position:relative; display:inline-block; left:-50%; margin-top:-50%; border:none; background:rgba(255,255,255,1); box-shadow:0px 2px 15px 4px #555; border-radius:20px; padding:0;">
                <div id="notification_lightbox_title" align="center" style="margin:25px 20px 0 20px;">
                </div>
                <div id="notification_lightbox_content" style="max-height:300px; overflow-y:auto; margin:0px 20px;">
                </div>
                <div align="center" style="margin-top:17px; padding:0 20px 20px 20px;">
                    <input type="button" style="padding:3px 10px; font-weight:900;" onclick="hide_and_empty_lightbox()" value="Close" />
                </div>
            </div>
        </div>
    </body>
</html>
        <?php
        // Flush the buffered output.
        ob_end_flush();
    }

}
?>
