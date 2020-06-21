<?php

use com\axelsmidt\freepilotlog\views as views;
use com\axelsmidt\freepilotlog\controllers as controllers;

require_once 'includes' . DIRECTORY_SEPARATOR . 'config.inc.php';
require_once 'includes' . DIRECTORY_SEPARATOR . 'header.inc.php';

new controllers\Logout();
header("Location: " . "index.php?msg=You are now signed out.");
