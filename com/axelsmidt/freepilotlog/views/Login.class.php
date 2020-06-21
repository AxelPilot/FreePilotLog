<?php

namespace com\axelsmidt\freepilotlog\views;

/**
 *
 */
class Login extends View {

    protected $url;
    protected $validation_exceptions;

    /**
     *
     */
    public function __construct() {
        parent::__construct();?>

        <h1>Sign Into Your Account</h1>

        <?php
        new Login_Form($this->url, $this->validation_exceptions);
    }

}
