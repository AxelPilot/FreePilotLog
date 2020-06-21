<?php

namespace com\axelsmidt\aslib;

/**
 *
 */
abstract class GUI {

    const FLOAT_LEFT = 'FormField FloatLeft';
    const FLOAT_RIGHT = 'FormField FloatRight';
    const NO_FLOAT = 'FormField NoFloat';

    protected $float;

    /**
     *
     */
    public function __construct($float = self::NO_FLOAT) {
        $this->float = $float;
    }

    /**
     *
     */
    abstract public function show();
}

?>
