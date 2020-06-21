<?php

namespace com\axelsmidt\aslib;

/**
 * Adds support for throwing and catching arrays as exceptions.
 */
class Exception extends \Exception {

    const THROW_ALL = 40; // Throw all exceptions.
    const THROW_VALIDATION = 30; // Throw validation exceptions and "below" only.
    const THROW_NO_VALIDATION = 29;
    const THROW_DB = 20; // Throw database exceptions and "below" only.
    const THROW_NO_DB = 19;
    const THROW_DB_ERROR = 10; // Throw database error exceptions and "below" only.
    const THROW_NO_DB_ERROR = 9;
    const THROW_NONE = 0; // Throw no exceptions.

    /**
     * @Override
     */
    public function __construct($message = "", $code = 0, $previous = NULL) {
        parent::__construct(json_encode($message), $code, $previous);
    }

    /**
     *
     */
    public function getArrayMessage() {
        return json_decode($this->getMessage(), true);
    }

}

?>