<?php

namespace com\axelsmidt\freepilotlog\views;

/**
 *
 */
class Register_User_Form {

    protected $validation_exceptions;

    /**
     *
     */
    public function __construct($validation_exceptions) {
        $this->validation_exceptions = $validation_exceptions;
        ?>

        <form action="register_user.php" method="post">
            <fieldset>
                <div class="FormField NoFloat">
                    <b>First Name:</b><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="text" id="firstname" name="firstname" size="41" maxlength="30"<?php
                        if (isset($_POST['firstname'])) {
                            ?> value="<?php echo $_POST['firstname']; ?>"<?php
                        }
                        ?> style="width:277px;<?php
                               if (isset($this->validation_exceptions['firstname'])) {
                                   ?> border-color: #F00;<?php
                               }
                               ?>" />
                    </div>

                    <div id="firstname_exception" class="validation_exception NoFloat">
                        <?php
                        if (isset($this->validation_exceptions) && isset($this->validation_exceptions['firstname'])) {
                            echo $this->validation_exceptions['firstname'];
                        }
                        ?>
                    </div>
                </div>

                <div class="FormField NoFloat">
                    <b>Last Name:</b><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="text" id="lastname" name="lastname" size="41" maxlength="30"<?php
                        if (isset($_POST['lastname'])) {
                            ?> value="<?php echo $_POST['lastname']; ?>"<?php
                        }
                        ?> style="width:277px;<?php
                               if (isset($this->validation_exceptions['lastname'])) {
                                   ?> border-color: #F00;<?php
                               }
                               ?>" />
                    </div>

                    <div id="lastname_exception" class="validation_exception NoFloat">
                        <?php
                        if (isset($this->validation_exceptions) && isset($this->validation_exceptions['lastname'])) {
                            echo $this->validation_exceptions['lastname'];
                        }
                        ?>
                    </div>
                </div>

                <div class="FormField NoFloat">
                    <b>Email Address:</b><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="text" id="email" name="email" size="41" maxlength="40"<?php
                        if (isset($_POST['email'])) {
                            ?> value="<?php echo $_POST['email']; ?>"<?php
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
                    <b>Password:</b> <small>Must be between 8-20 characters.</small><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="password" id="password" name="password1" size="41" maxlength="20" style="width:277px;<?php
                        if (isset($this->validation_exceptions['new_password'])) {
                            ?> border-color: #F00;<?php
                        }
                        ?>" /><br />
                    </div>

                    <div id="password_exception" class="validation_exception NoFloat">
                        <?php
                        if (isset($this->validation_exceptions['new_password'])) {
                            echo $this->validation_exceptions['new_password'];
                        }
                        ?>
                    </div>
                </div>

                <div class="FormField NoFloat">
                    <b>Confirm password:</b><br />
                    <div class="FloatLeft" style="margin-right:8px";>
                        <input type="password" id="confirmed_password" name="password2" size="41" maxlength="20" style="width:277px;<?php
                        if (isset($this->validation_exceptions['confirmed_password'])) {
                            ?> border-color: #F00;<?php
                        }
                        ?>" />
                    </div>

                    <div id="confirmed_password_exception" class="validation_exception NoFloat">
                        <?php
                        if (isset($this->validation_exceptions['confirmed_password'])) {
                            echo $this->validation_exceptions['confirmed_password'];
                        }
                        ?>
                    </div>
                </div>
            </fieldset>

            <div align="center">
                <input type="button" name="cancel" value="Cancel" onclick="window.location = 'index.php?msg=Registration was canceled.'" />
                <input type="submit" name="submit" value="Register" />
            </div>

            <input type="hidden" name="submitted" value="TRUE" />
        </form>
        <?php
    }

}
