<?php

use com\axelsmidt\freepilotlog\views as views;

require_once 'includes' . DIRECTORY_SEPARATOR . 'config.inc.php';
require_once 'includes' . DIRECTORY_SEPARATOR . 'header.inc.php';
?>
<script type="text/javascript" src="./includes/register_user.js" charset="utf-8"></script>
<?php

new views\Register_User();
new views\Footer();
