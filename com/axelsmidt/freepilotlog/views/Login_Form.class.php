<?php

namespace com\axelsmidt\freepilotlog\views;

/**
 * Sign in Form.
 */
class Login_Form {

    protected $url;
    protected $validation_exceptions;

    /**
     *
     */
    public function __construct($url, $validation_exceptions) {
        $this->url = $url;
        $this->validation_exceptions = $validation_exceptions;

        if (isset($_GET['msg'])) {
            ?><div class="IndexMessage"><?php echo $_GET['msg']; ?></div><?php
        }

        if (isset($_GET['error'])) {
            ?><div class="IndexError"><?php echo $_GET['error']; ?></div><?php
        }
        ?>
        <div class="txtbg2" style="margin-bottom:20px;">Your browser must accept cookies for you to sign in.</div>
        <form action="login.php" method="post">
            <fieldset>
                <div class="FormField NoFloat">
                    <b>Username (email address):</b><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="text" id="email" name="email" size="40" maxlength="40"<?php
                        if (isset($_POST['email'])) {
                            ?> value="<?php echo $_POST['email'] ?>"<?php
                               }
                               ?> style="width:277px;<?php
                               if (isset($this->validation_exceptions['email'])) {
                                   ?> border-color: #F00;<?php
                               }
                               ?>" />
                    </div>

                    <div id="email_exception" class="validation_exception NoFloat">
                        <?php
                        if (isset($this->validation_exceptions) && isset($this->validation_exceptions['email'])) {
                            echo $this->validation_exceptions['email'];
                        }
                        ?>
                    </div>
                </div>

                <div class="FormField NoFloat">
                    <b>Password:</b><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="password" id="password" name="password" size="40" maxlength="20" style="width:277px;<?php
                        if (isset($validation_exceptions['password'])) {
                            ?> border-color: #F00;<?php
                               }
                               ?>" /><br />
                    </div>

                    <div id="password_exception" class="validation_exception NoFloat">
                        <?php
                        if (isset($this->validation_exceptions['password'])) {
                            echo $this->validation_exceptions['password'];
                        }
                        ?>
                    </div>
                </div>

                <div class="FormField NoFloat" style="color:#000; margin-top:15px;">
                    <a href="forgot_password.php"><b>Forgotten password?</b></a>
                </div>

                <div class="FormField NoFloat" style="color:#000; margin-top:15px;">
                    Don't have a user account?<br />
                    <a href="register_user.php"><b>Register here</b></a><br />
                </div>

            </fieldset>

            <div align="center">
                <input type="button" name="cancel" value="Cancel" onclick="window.location = 'index.php'" />
                <input type="submit" name="submit" value="Sign in" />
            </div>

            <input type="hidden" name="url" value="<?php echo $this->url; ?>" />
            <input type="hidden" name="submitted" value="TRUE" />
        </form>
        <?php
    }

}
