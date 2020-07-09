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
        ?><div class="<?php echo $this->float; ?>">
        <input type="<?php echo $this->type; ?>" name="<?php echo $this->name; ?>" value="<?php echo $this->value; ?>"<?php
        if (count($this->styles) > 0) {
            ?> style="<?php
            foreach ($this->styles as $style) {
                echo $style;
            }
            ?>" />
            <?php
        }
        ?></div>
        <?php
        ;
    }

}
?>
