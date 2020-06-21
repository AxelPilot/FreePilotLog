<?php

namespace com\axelsmidt\aslib;

class MySQLi extends \MySQLi {

    /**
     * @Override
     */
    public function __construct() {
        if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_NAME') && defined('DB_PORT') && defined('DB_SOCKET')) {
            parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, DB_SOCKET);
        } elseif (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_NAME') && defined('DB_PORT')) {
            parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        } elseif (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_NAME')) {
            parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        } elseif (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD')) {
            parent::__construct(DB_HOST, DB_USER, DB_PASSWORD);
        } elseif (defined('DB_HOST') && defined('DB_USER')) {
            parent::__construct(DB_HOST, DB_USER);
        } elseif (defined('DB_HOST')) {
            parent::__construct(DB_HOST);
        } else {
            parent::__construct();
        }
    }

    /**
     *
     */
    public function escape_data($data) {
        if (ini_get('magic_quotes_gpc')) {
            $data = stripslashes($data);
        }
        return parent::real_escape_string(trim($data));
    }

    /**
     *
     */
    public static function connect2db($exception_message, $throw_exceptions = Exception::THROW_ALL) {
        $ok = true;
        // Connect to the database.
        $mysqli = new MySQLi();
        if ($mysqli->connect_error) {
            if ($throw_exceptions >= Exception::THROW_DB_ERROR) {
                throw new DbErrorException($exception_message);
            }
            $ok = false;
        }
        return $ok ? $mysqli : false;
    }

}

?>
