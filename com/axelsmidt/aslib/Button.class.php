<?php

namespace com\axelsmidt\aslib;

/**
 *
 */
class Button extends GUI {

    protected $type = 'button';
    protected $name;
    protected $value;
    protected $styles = array();

    /**
     *
     */
    public function __construct($value = '', $name = '', $float = parent::NO_FLOAT) {
        if ($name == '') {
            $this->make_submit();
            $this->value = $value == '' ? 'Submit' : $value;
        } else {
            $this->value = $value;
        }
        parent::__construct($float);
    }

    /**
     *
     */
    public function make_submit() {
        $this->type = 'submit';
        $this->name = 'submit';
    }

    /**
     *
     */
    public function add_label($label) {
        $this->value = $label;
    }

    /**
     *
     */
    public function add_style($style) {
        $this->styles[] = $style;
    }

    /**
     *
     */
    public function show() {
        echo '<div class="' . $this->float . '">' . "\r\n";
        echo '<input type="' . $this->type . '" name="' . $this->name . '" value="' . $this->value . '"';

        if (count($this->styles) > 0) {
            echo ' style="';
            foreach ($this->styles as $style) {
                echo $style;
            }
            echo '" />' . "\r\n";
        }

        echo '</div>' . "\r\n\r\n";
        ;
    }

}

?>
