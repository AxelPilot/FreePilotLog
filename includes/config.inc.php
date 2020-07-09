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

use com\axelsmidt\freepilotlog\controllers as controllers;

// ************************************************************************
// ************************************************************************
// No error messages will be shown to the user.

error_reporting(E_ALL);
// error_reporting( 0 );
// ************************************************************************
// ************************************************************************
// ** MySQL settings ** //
define('DB_NAME', 'freepilotlog');  // The name of the database
define('DB_USER', 'root');          // Your MySQL Username
define('DB_PASSWORD', '');          // Your MySQL Password
define('DB_HOST', '');              // Your MySQL Hostmane
define('DB_CHARSET', 'utf8');       // The character set of the database
define('DB_COLLATE', '');

// ************************************************************************
// ************************************************************************
// You can have multiple installations in one database if you give each 
// installation a unique prefix.
// Only numbers, letters, and underscores please!

define('TABLE_PREFIX', '');

// ************************************************************************
// ************************************************************************

// ************************************************************************
// ************************************************************************

define('SITE_URL', 'http://www.freepilotlog.com');

// ************************************************************************
// ************************************************************************


/**
 * Custom error Handler.
 */
set_error_Handler(function ($errno, $errstr, $errfile, $errline) {
    $error = date('d.m.Y H:i') . ' ';
    $error .= $errfile . ' ';
    $error .= $errline . ' ';
    $error .= $errno . ' ';
    $error .= $errstr . "\r\n";

    error_log($error, 3, "error/errorlog.txt");
}, E_ALL);

/**
 * Custom class autoloader.
 */
spl_autoload_register(function ($class) {
    require_once $class . '.class.php';
});

/**
 * Returns true if the user is logged in.
 * Returns false if the user is not logged in.
 */
function is_loggedIn() {
    // If no user_ID or first_name variable exists, redirect the user.
    return (!isset($_SESSION['email']) || !isset($_SESSION['firstname']) || !isset($_SESSION['lastname'])) ? false : true;
}

ob_start(); // Start output buffering.
session_start(); // Initialize a session.
