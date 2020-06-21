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

class User extends Model {

    protected $user_ID;
    protected $firstname;
    protected $lastname;
    protected $address;
    protected $postal_code;
    protected $city;
    protected $phone;
    protected $email;
    protected $password = NULL;
    protected $activation_code = NULL;
    protected $registration_date;
    protected $admin = 'N';
    protected $validation_messages = array();

    /**
     *
     */
    public function __construct($user_ID,
            $email = NULL,
            $new_password = NULL,
            $confirmed_password = NULL,
            $lastname = NULL,
            $firstname = NULL,
            $address = NULL,
            $postal_code = NULL,
            $city = NULL,
            $phone = NULL,
            $throw_exceptions = aslib\Exception::THROW_ALL) {
        // Retrieving the user account from the database based on user_ID.
        if (isset($user_ID)) {
            $this->user_ID = $user_ID;
            if (!$this->retrieve_from_db($throw_exceptions)) {
                if ($throw_exceptions >= aslib\Exception::THROW_DB) {
                    throw new aslib\DbException('Brukeren finnes ikke.');
                }
            }
        } elseif (isset($email)) {
            $this->email = $email;

            // Verifying a correct password for the purpose of logging into an existing user
            // account if $new_password is set.
            if (isset($new_password) && !isset($confirmed_password) && !isset($lastname) && !isset($firstname) && !isset($address) && !isset($postal_code) && !isset($city) && !isset($phone)) {
                if ($this->validate_email_and_password($email, $new_password, $throw_exceptions) === true) {
                    if ($this->has_activated($new_password, $throw_exceptions)) {
                        // Retrieving the user account data.
                        if (!$this->retrieve_from_db($throw_exceptions)) {
                            if ($throw_exceptions >= aslib\Exception::THROW_DB) {
                                throw new aslib\DbException('Brukeren finnes ikke.');
                            }
                        }
                    } else {
                        throw new aslib\DbException('Du har ikke aktivert kontoen din.');
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
            // of registering new user accounts if the user is not previously registered in 
            // the database.
            else {
                $v = $this->validate_data($lastname, $firstname, $address, $postal_code, $city, $phone,
                        $email, $new_password, $confirmed_password, $throw_exceptions);
                if ($v === true) {
                    $this->lastname = $lastname;
                    $this->firstname = $firstname;
                    $this->address = $address;
                    $this->postal_code = $postal_code;
                    $this->city = $city;
                    $this->phone = $phone;
                    $this->email = $email;

                    if (isset($new_password)) {
                        $this->password = $new_password;
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
                throw new aslib\DbErrorException($technical_error);
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     *
     */
    public function has_activated($password, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $ok = false;
        if ($this->exists_in_db($throw_exceptions)) {
            if ($mysqli = aslib\MySQLi::connect2db($technical_error, $throw_exceptions)) {
                // Query the database.
                $query = "
				SELECT user_ID
				FROM " . TABLE_PREFIX . "user
				WHERE
				email='" . $this->email . "'
				AND
				password=SHA('" . $password . "')
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
     *
     */
    public function apply_for_admin($throw_exceptions = aslib\Exception::THROW_ALL) {
        $request = new Admin_Request($this, $throw_exceptions);
        return $request->apply($throw_exceptions);
    }

    /**
     *
     */
    public function approve_admin($admin_activation_code, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $request = new Admin_Request($this, $throw_exceptions);
        return $request->approve($admin_activation_code, Admin_Request::APPROVED, $throw_exceptions);
    }

    /**
     *
     */
    public function deny_admin($admin_activation_code, $throw_exceptions = aslib\Exception::THROW_ALL) {
        $request = new Admin_Request($this, $throw_exceptions);
        return $request->approve($admin_activation_code, Admin_Request::DENIED, $throw_exceptions);
    }

    /**
     *
     */
    public function create_activation_code() {
        // Create the activation code
        return md5(uniqid(rand(), true));
    }

    /**
     * Saves the user to the database.
     *
     * Returns the auto generated user_ID upon success.
     * Returns false upon failure.
     */
    public function save_to_db($throw_exceptions = aslib\Exception::THROW_ALL) {
        if (!$this->exists_in_db($throw_exceptions)) {
            if ($mysqli = aslib\MySQLi::connect2db($technical_error, $throw_exceptions)) {
                // Add the user to the database.
                $query = "
				INSERT INTO " . TABLE_PREFIX . "user (firstname, lastname, address, postal_code, city, phone, email, password, activation_code, registration_date) 
				VALUES ('" . $mysqli->escape_data($this->firstname) . "',
				'" . $mysqli->escape_data($this->lastname) . "',
				'" . $mysqli->escape_data($this->address) . "',
				'" . $mysqli->escape_data($this->postal_code) . "',
				'" . $mysqli->escape_data($this->city) . "',
				'" . $mysqli->escape_data($this->phone) . "',
				'" . $mysqli->escape_data($this->email) . "', ";

                $query .= isset($this->password) ?
                        "SHA('" . $mysqli->escape_data($this->password) . "'),
				'" . $this->activation_code . "', " :
                        "NULL, NULL, ";

                $query .= "NOW())";

                $result = $mysqli->query($query);

                // If the data were successfully inserted into the database...
                if ($mysqli->affected_rows == 1) {
                    // Retrieves the auto generated user_ID from the database.
                    $this->user_ID = $mysqli->insert_id;

                    $mysqli->close();

                    if (isset($this->password)) {
                        unset($this->password);
                        if (!$this->send_confirmation_email()) {
                            if ($throw_exceptions >= aslib\Exception::THROW_DB_ERROR) {
                                throw new aslib\DbErrorException('Sending av bekreftelse pr e-post feilet.');
                            }
                            return false;
                        }
                    }
                    return $this->user_ID;
                } else { // If query was unsuccessful.
                    $mysqli->close();

                    unset($this->password);
                    if ($throw_exceptions >= aslib\Exception::THROW_DB_ERROR) {
                        throw new aslib\DbErrorException($technical_error);
                    }
                    return false;
                }
            }
        } else {
            unset($this->password);
            if ($throw_exceptions >= aslib\Exception::THROW_DB) {
                throw new aslib\DbException('Den e-postadressen er allerede blitt registrert.<br />
				Hvis du har glemt passordet ditt, kan du bruke \'Glemt passord\'-lenken for å nullstille passordet ditt.');
            }
            return false;
        }
    }

    /**
     *
     */
    protected function save_new_password_to_db($password) {
        $ok = false;
        if ($mysqli = aslib\MySQLi::connect2db($technical_error)) {
            $query = "
			UPDATE " . TABLE_PREFIX . "user
			SET password = SHA('" . $password . "')
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
            $this->user_ID = $a['user_ID'];
            $this->firstname = $a['firstname'];
            $this->lastname = $a['lastname'];
            $this->address = $a['address'];
            $this->postal_code = $a['postal_code'];
            $this->city = $a['city'];
            $this->phone = $a['phone'];
            $this->email = $a['email'];
            $this->activation_code = $a['activation_code'];
            $this->registration_date = $a['registration_date'];
            $this->admin = $a['admin'];
            $this->activation_admin = $a['activation_admin'];

            return $a;
        } else {
            return false;
        }
    }

    /**
     * Checks if the user exists in the database.
     *
     * Returns the user's user_ID if the user exists in the database.
     * Returns false if the user doesn't exist in the database.
     */
    public function exists_in_db($throw_exceptions = aslib\Exception::THROW_ALL) {
        if ($mysqli = aslib\MySQLi::connect2db($technical_error, $throw_exceptions)) {
            // Checking if an identical user is already registered in the database.
            $query = "
			SELECT user_ID, firstname, lastname, address, postal_code, city, phone, email, activation_code, registration_date, admin, activation_admin
			FROM " . TABLE_PREFIX . "user
			WHERE ";
            $query .= isset($this->user_ID) ?
                    "user_ID = " . $this->user_ID :
                    "UPPER(email) = UPPER('" . $this->email . "')";

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
                    throw new aslib\DbErrorException($technical_error);
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
        $body = "Kjære " . $this->firstname . " " . $this->lastname . "!\r\n\r\n";
        $body .= "Takk for din registrering.\r\n\r\n";
        $body .= "Vennligst klikk på lenken under for å aktivere kontoen din:\r\n\r\n";
        $body .= getBaseUrl() . "/activate.php?x=" . $this->user_ID . "&y=" . $this->activation_code;

        $subject = 'Registreringsbekreftelse';

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
            $exceptions['email'] = 'Ugyldig e-postadresse!';
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
        if ($mysqli = aslib\MySQLi::connect2db($technical_error, $throw_exceptions)) {
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
     * Returns true if the user is an administrator.
     * Returns false otherwise.
     */
    public function is_admin() {
        return ( $this->admin == 'Y' );
    }

    /**
     * Returns true if the user is not an administrator and has not applied 
     * to become an administrator.
     *
     * Returns false otherwise.
     */
    public function applied_for_admin() {
        return ( $this->is_admin() || ( $this->admin == 'applied' ) );
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
     * Admin Activation Code get function.
     */
    public function get_admin_activation_code() {
        return $this->activation_admin;
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
