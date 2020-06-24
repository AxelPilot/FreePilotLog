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

namespace com\axelsmidt\freepilotlog\models;

use com\axelsmidt\aslib as aslib;

/**
 *
 */
class User extends Model {

    protected $email;
    protected $firstname;
    protected $lastname;
    protected $password = NULL;
    protected $activation_code = NULL;
    protected $registration_date;
    protected $validation_messages = array();

    /**
     *
     */
    public function __construct(
            $email = NULL,
            $password = NULL,
            $confirmed_password = NULL,
            $lastname = NULL,
            $firstname = NULL,
            $throw_exceptions = aslib\Exception::THROW_ALL) {

        // Retrieving the user account from the database based on email.
        if (isset($email) && (!isset($lastname) || !isset($firstname))) {
            $this->email = $email;
            
            // Throw an exception if the user doesn't exist.
            if (!$this->retrieve_from_db($throw_exceptions)) {
                if ($throw_exceptions >= aslib\Exception::THROW_DB) {
                    throw new aslib\DbException('The user doesn\'t exist.');
                }
            }

            // Verifying a correct password for the purpose of logging into an 
            // existing user account if $new_password is set.
            if (isset($password) && !isset($confirmed_password) && !isset($lastname) && !isset($firstname)) {
                if ($this->validate_email_and_password($email, $password, $throw_exceptions) === true) {
                    if ($this->has_activated($password, $throw_exceptions)) {
                        // Retrieving the user account data.
                        if (!$this->retrieve_from_db($throw_exceptions)) {
                            if ($throw_exceptions >= aslib\Exception::THROW_DB) {
                                throw new aslib\DbException('The user doesn\'t exist.');
                            }
                        }
                    } else {
                        throw new aslib\DbException('You have not activated your account.');
                    }
                }
            }

            // Retrieving the user account from the database based on the user's email address.
            elseif ($this->exists_in_db($throw_exceptions)) {
                if ($this->validate_email($email, $throw_exceptions) === true) {
                    // Retrieving the user account data.
                    $this->retrieve_from_db($throw_exceptions);
                }
            }

            // Creating a new user based on the constructor's input parameters for the purpose 
            // of registering new user accounts, if the user is not previously registered in 
            // the database.
            else {
                $v = $this->validate_data($email, $lastname, $firstname,
                        $password, $confirmed_password, $throw_exceptions);
                if ($v === true) {
                    $this->email = $email;
                    $this->lastname = $lastname;
                    $this->firstname = $firstname;

                    if (isset($password)) {
                        $this->password = $password;
                        $this->activation_code = $this->create_activation_code();
                    }
                } elseif (is_array($v) && ( $throw_exceptions < aslib\Exception::THROW_VALIDATION )) {
                    $this->validation_messages = array_merge($this->validation_messages, $v);
                }
            }
        }
    }

    /**
     *
     */
    public function change_password($old_password, $new_password, $confirmed_password, $throw_exceptions = aslib\Exception::THROW_ALL) {
        if (isset($old_password) && isset($new_password) && isset($confirmed_password) && ( $this->validate_password($old_password, $new_password, $confirmed_password) === true )) {
            if (!$this->save_new_password_to_db($new_password) && $throw_exceptions >= aslib\Exception::THROW_DB_ERROR) {
                throw new aslib\DbErrorException("We apologize, but a technical error has occured.");
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Checks if an account, identified by the user's email address, has
     * been activated or not.
     * 
     * Returns true if the account is activated.
     * Returns false if the account has not been activated.
     */
    public function has_activated($password, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $ok = false;
        if ($this->exists_in_db($throw_exceptions)) {
            if ($mysqli = aslib\MySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
                // Query the database.
                $query = "
				SELECT email
				FROM " . TABLE_PREFIX . "user
				WHERE
				UPPER(email)=UPPER('" . $this->email . "')
				AND
				password=SHA2('" . $password . "', 256)
				AND
				activation_code IS NULL";

                if (( $result = $mysqli->query($query) ) && ( $result->num_rows == 1 )) {
                    $result->free();
                    $ok = true;
                }
            }
        }
        return $ok;
    }

    /**
     * Creates and returns an activation code to be stored in the database
     * along with a newly registered user.
     */
    public function create_activation_code() {
        // Create the activation code
        return md5(uniqid(rand(), true));
    }

    /**
     * Saves the user to the database.
     *
     * Returns the user's email address upon success.
     * Returns false upon failure.
     */
    public function save_to_db($throw_exceptions = aslib\Exception::THROW_ALL) {
        if (!$this->exists_in_db($throw_exceptions)) {
            if ($mysqli = aslib\MySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
                // Add the user to the database.
                $query = "
				INSERT INTO " . TABLE_PREFIX . "user (email, firstname, lastname, address, postal_code, city, phone, password, activation_code, registration_date) 
				VALUES ('" . $mysqli->escape_data($this->email) . "',
                                '" . $mysqli->escape_data($this->firstname) . "',
				'" . $mysqli->escape_data($this->lastname) . "',
				'" . $mysqli->escape_data($this->address) . "',
				'" . $mysqli->escape_data($this->postal_code) . "',
				'" . $mysqli->escape_data($this->city) . "',
				'" . $mysqli->escape_data($this->phone) . "', ";

                $query .= isset($this->password) ?
                        "SHA2('" . $mysqli->escape_data($this->password) . "', 256),
				'" . $this->activation_code . "', " :
                        "NULL, NULL, ";

                $query .= "NOW())";

                $result = $mysqli->query($query);

                // If the data were successfully inserted into the database...
                if ($mysqli->affected_rows == 1) {
                    $mysqli->close();

                    if (isset($this->password)) {
                        unset($this->password);
                        if (!$this->send_confirmation_email()) {
                            if ($throw_exceptions >= aslib\Exception::THROW_DB_ERROR) {
                                throw new aslib\DbErrorException('The confirmation email couldn\'t be sent.');
                            }
                            return false;
                        }
                    }
                    return $this->email;
                } else { // If query was unsuccessful.
                    $mysqli->close();

                    unset($this->password);
                    if ($throw_exceptions >= aslib\Exception::THROW_DB_ERROR) {
                        throw new aslib\DbErrorException("We apologize, but a technical error has occured.");
                    }
                    return false;
                }
            }
        } else {
            unset($this->password);
            if ($throw_exceptions >= aslib\Exception::THROW_DB) {
                throw new aslib\DbException('This email address has already been registered.<br />
				If you have forgotten your passwork, you can use the \'Forgotten password\' link to reset your password.');
            }
            return false;
        }
    }

    /**
     *
     */
    protected function save_new_password_to_db($password) {
        $ok = false;
        if ($mysqli = aslib\MySQLi::connect2db("We apologize, but a technical error has occured.")) {
            $query = "
			UPDATE " . TABLE_PREFIX . "user
			SET password = SHA2('" . $password . "', 256)
			WHERE
			user_ID = " . $_SESSION['user_ID'];

            if ($mysqli->query($query) && ( $mysqli->affected_rows == 1 )) {
                $ok = true;
            }
            $mysqli->close();
        }
        return $ok;
    }

    /**
     * Checks whether the user exists in the database, and if so,
     * retrieves the correct user ID from the database and stores it
     * in the user object.
     *
     * Returns the user ID if the event is found in the database.
     * Returns false if the user is not found in the database.
     */
    protected function retrieve_from_db($throw_exceptions = aslib\Exception::THROW_ALL) {
        if (( $a = $this->exists_in_db($throw_exceptions) ) && is_array($a)) {
            $this->email = $a['email'];
            $this->firstname = $a['firstname'];
            $this->lastname = $a['lastname'];
            $this->address = $a['address'];
            $this->postal_code = $a['postal_code'];
            $this->city = $a['city'];
            $this->phone = $a['phone'];
            $this->activation_code = $a['activation_code'];
            $this->registration_date = $a['registration_date'];

            return $a;
        } else {
            return false;
        }
    }

    /**
     * Checks if the user exists in the database.
     *
     * Returns the user's email if the user exists in the database.
     * Returns false if the user doesn't exist in the database.
     */
    public function exists_in_db($throw_exceptions = aslib\Exception::THROW_ALL) {
        if ($mysqli = aslib\MySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
            // Checking if an identical user is already registered in the database.
            $query = "
			SELECT email, firstname, lastname, address, postal_code, city, phone, activation_code, registration_date
			FROM " . TABLE_PREFIX . "user
			WHERE
                        UPPER(email) = UPPER('" . $this->email . "')";

            if ($result = $mysqli->query($query)) {
                // If the user is previously registered.
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $user = $row;
                    $result->close();
                    $mysqli->close();
                    return $user;
                } else { // Did NOT find the user in the database.
                    $result->close();
                    $mysqli->close();
                    return false;
                }
            } else {
                $mysqli->close();
                if ($throw_exceptions >= aslib\Exception::THROW_DB_ERROR) {
                    throw new aslib\DbErrorException("We apologize, but a technical error has occured.");
                }
                return false;
            }
        }
    }

    /**
     *
     */
    protected function send_confirmation_email() {
        // Compose the confirmation email.
        $body = "Dear " . $this->firstname . " " . $this->lastname . "!\r\n\r\n";
        $body .= "Thank you for registering a new account with Free Pilot Logbook.\r\n\r\n";
        $body .= "Please click the link below to activate your account:\r\n\r\n";
        $body .= getBaseUrl() . "/activate.php?x=" . $this->user_ID . "&y=" . $this->activation_code;

        $subject = 'Registration confirmation';

        $email = new Email($this->email, $subject, $body);
        return $email->send();
    }

    /**
     * Validates the user data.
     *
     * Returns true if all validations were successful.
     * Returns an array with exception message(s) if one or more of the validations failed.
     */
    public function validate_data($lastname,
            $firstname,
            $address,
            $postal_code,
            $city,
            $phone,
            $email,
            $new_password = NULL,
            $confirmed_password = NULL,
            $throw_exceptions = aslib\Exception::THROW_ALL) {
        $exceptions = array();

        // Check for a first name.
        if (!isset($firstname) || ( preg_match("/^[a-zæøåÆØÅ\.\' \-]{2,30}$/i", trim($firstname)) !== 1 )) {
            $exceptions['firstname'] = 'Ugyldig fornavn!';
        }

        // Check for a last name.
        if (!isset($lastname) || ( preg_match("/^[a-zæøåÆØÅ\.\' \-]{2,30}$/i", trim($lastname)) !== 1 )) {
            $exceptions['lastname'] = 'Ugyldig etternavn!';
        }

        // Check for a valid address.
        if (!isset($address) || ( preg_match("/^[a-z0-9æøåÆØÅ\.\' \-]{2,45}$/i", trim($address)) !== 1 )) {
            $exceptions['address'] = 'Ugyldig adresse!';
        }

        // Check for a valid postal code.
        if (!isset($postal_code) || ( preg_match("/^[0-9]{4,5}$/", trim($postal_code)) !== 1 )) {
            $exceptions['postal_code'] = 'Ugyldig postnummer!';
        }

        // Check for a city.
        if (!isset($city) || ( preg_match("/^[a-zæøåÆØÅ\.\' \-]{2,40}$/i", trim($city)) !== 1 )) {
            $exceptions['city'] = 'Ugyldig poststed!';
        }

        // Check for a phone number.
        if (!isset($phone) || ( preg_match("/^[0-9]{2,20}$/", trim($phone)) !== 1 )) {
            $exceptions['phone'] = 'Ugyldig telefonnummer!';
        }

        // Check for an email address.
        $e = $this->validate_email($email, aslib\Exception::THROW_NO_VALIDATION);
        if ($e !== true) {
            $exceptions = array_merge($exceptions, $e);
        }

        // Check for a password, and match against the confirmed password.
        if (isset($new_password)) {
            $p = $this->validate_password(NULL, $new_password, $confirmed_password, aslib\Exception::THROW_NO_VALIDATION);
            if ($p !== true) {
                $exceptions = array_merge($exceptions, $p);
            }
        }

        if (count($exceptions) > 0) {
            if ($throw_exceptions >= aslib\Exception::THROW_VALIDATION) {
                throw new aslib\FormValidationException($exceptions);
            }
            return $exceptions;
        } else {
            return true;
        }
    }

    /**
     * Validates the user's email address and password.
     *
     * Returns true if the validation was successful.
     * Returns an exception message if the validation failed.
     */
    protected function validate_email_and_password($email, $password, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $exceptions = array();

        // Check for a valid email address.
        $e = $this->validate_email($email, aslib\Exception::THROW_NO_VALIDATION);
        if ($e !== true) {
            $exceptions = array_merge($exceptions, $e);
        }

        // Check for a valid password.
        $p = $this->validate_password($password, NULL, NULL, aslib\Exception::THROW_NO_VALIDATION);
        if ($p !== true) {
            $exceptions = array_merge($exceptions, $p);
        }

        if (count($exceptions) > 0) {
            if ($throw_exceptions >= aslib\Exception::THROW_VALIDATION) {
                throw new aslib\FormValidationException($exceptions);
            }
            return $exceptions;
        } else {
            return true;
        }
    }

    /**
     * Validates the user's email address.
     *
     * Returns true if the validation was successful.
     * Returns an exception message if the validation failed.
     */
    protected function validate_email($email, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $exceptions = array();

        // Check for an email address.
        if (!isset($email) || ( $email == "" ) || ( preg_match("/^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$/i", trim($email)) !== 1 )) {
            $exceptions['email'] = 'You entered an invalid email address.';
        }

        if (count($exceptions) > 0) {
            if ($throw_exceptions >= aslib\Exception::THROW_VALIDATION) {
                throw new aslib\FormValidationException($exceptions);
            }
            return $exceptions;
        } else {
            return true;
        }
    }

    /**
     * Validates the user password.
     *
     * Returns true if the validation was successful.
     * Returns an exception message if the validation failed.
     */
    protected function validate_password($old_password,
            $new_password = NULL,
            $confirmed_password = NULL,
            $throw_exceptions = aslib\Exception::THROW_ALL) {
        $exceptions = array();

        if (isset($old_password)) {
            if (preg_match("/^[[:alnum:]]{4,20}$/i", trim($old_password)) !== 1) {
                $exceptions['password'] = 'You entered an invalid password.';
            } elseif (!$this->match_password_against_db($old_password, $throw_exceptions)) {
                $exceptions['password'] = 'The username and/or password are incorrect.';
            }
        }

        if (( count($exceptions) <= 0 ) && isset($new_password)) {
            if (preg_match("/^[[:alnum:]]{4,20}$/i", trim($new_password)) !== 1) {
                $exceptions['new_password'] = 'You entered an invalid password.';
            }

            // Match the password against the confirmed password.
            if ($new_password != $confirmed_password) {
                $exceptions['confirmed_password'] = 'The passwords do not match.';
            }
        }

        if (count($exceptions) > 0) {
            if ($throw_exceptions >= aslib\Exception::THROW_VALIDATION) {
                throw new aslib\FormValidationException($exceptions);
            }
            return $exceptions;
        } else {
            return true;
        }
    }

    /**
     * Matches the given password against the one stored in the database
     * for this user.
     *
     * Identifies the user in the database by user_ID if the user_ID is set
     * in the user object. Otherwise, email address is used to identify the
     * user in the database.
     *
     * Returns true if the password given in the method's input parameter 
     * matches the one that is stored in the database.
     */
    protected function match_password_against_db($password, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $ok = false;
        // Connect to the database.
        if ($mysqli = aslib\MySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
            // Retrieve the user's current password from the database
            // and compare it against the entered password.
            $query = "
			SELECT password
			FROM " . TABLE_PREFIX . "user
			WHERE ";

            $query .= isset($this->user_ID) ?
                    "user_ID = " . $this->user_ID :
                    "UPPER(email) = UPPER('" . $this->email . "')";

            $query .= "		
			AND
			password LIKE SHA('" . trim($password) . "')";

            if ($result = $mysqli->query($query)) {
                // Check for a match between the entered 'old' password and the one on file.
                if ($result->num_rows == 1) {
                    $ok = true;
                }
                $result->close();
            }
            $mysqli->close();
        }
        return $ok;
    }

    /**
     * User_ID get function.
     */
    public function get_user_ID() {
        return $this->user_ID;
    }

    /**
     * Firstname get function.
     */
    public function get_firstname() {
        return $this->firstname;
    }

    /**
     * Lastname get function.
     */
    public function get_lastname() {
        return $this->lastname;
    }

    /**
     * Lastname get function.
     */
    public function get_address() {
        return $this->address;
    }

    /**
     * Lastname get function.
     */
    public function get_postal_code() {
        return $this->postal_code;
    }

    /**
     * Lastname get function.
     */
    public function get_city() {
        return $this->city;
    }

    /**
     * Phone get function.
     */
    public function get_phone() {
        return $this->phone;
    }

    /**
     * Email get function.
     */
    public function get_email() {
        return $this->email;
    }

    /**
     * Validation_messages get function.
     */
    public function get_validation_messages() {
        $v = $this->validation_messages;
        unset($this->validation_messages);
        return $v;
    }

}
