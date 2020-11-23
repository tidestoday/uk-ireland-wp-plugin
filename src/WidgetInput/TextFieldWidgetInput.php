<?php

namespace TidesToday\TideTimes\WidgetInput;

/**
 * Class TextFieldWidgetInput
 * @package TidesToday\TideTimes\WidgetInput
 */
class TextFieldWidgetInput extends BaseWidgetInput
{


    /**
     * TextFieldWidgetInput constructor.
     * @param $id
     * @param $name
     * @param null|string|int $value
     * @param null|string $label
     * @param null|string $description
     * @param null|string $placeholder
     */
    public function __construct($id, $name, $value = null, $label = null, $description = null, $placeholder = null)
    {
        parent::__construct($id, $name, $value, $label, $description, $placeholder);
    }

    /**
     * Compiles the field.
     * @return string
     */
    public function compile()
    {
        return
            "<p>" .
            "<label for='{$this->id}' class='title_label'>{$this->label}</label>" .
            "<input type='text' id='{$this->id}' name='{$this->name}' class='widefat' placeholder='{$this->placeholder}' value='$this->value'>" .
            "<span class='description'>{$this->description}</span>" .
            "</p>";
    }

    /**
     * Renders the field.
     */
    public function render()
    {
        echo $this->compile();
    }
}
