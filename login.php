<?php

use com\axelsmidt\freepilotlog\views as views;
use com\axelsmidt\freepilotlog\controllers as controllers;

require_once 'includes' . DIRECTORY_SEPARATOR . 'config.inc.php';
require_once 'includes' . DIRECTORY_SEPARATOR . 'header.inc.php';
?>
<script type="text/javascript" src="./includes/login.js" charset="utf-8"></script>
<?php

new views\Login();
new views\Footer();
