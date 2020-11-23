<?php

namespace TidesToday\TideTimes\WidgetInput;

/**
 * Class SelectWidgetInput
 * @package TidesToday\TideTimes\WidgetInput
 */
class SelectWidgetInput extends BaseWidgetInput
{
    /** @var array $options */
    private $options = [];

    /**
     * SelectWidgetInput constructor.
     * @param $id
     * @param $name
     * @param null|string|int $value
     * @param null|string $label
     * @param null|string $description
     * @param array $options
     */
    public function __construct($id, $name, $value = null, $label = null, $description = null, $options = [])
    {
        $this->options = $options;

        parent::__construct($id, $name, $value, $label, $description);
    }

    /**
     * Gets option string.
     *
     * @param $value
     * @param $label
     * @return string
     */
    private function getOptionString($value, $label)
    {
        $selected = selected($this->value, $value, false);
        return "<option value='{$value}' $selected>$label</option>";
    }

    /**
     * Gets the option group string.
     *
     * @param $label
     * @param array $options
     * @return string
     */
    private function getOptionGroupString($label, $options = [])
    {
        $response = "<optgroup label='$label'>";

        foreach ($options as $value => $label) {
            $response .= $this->getOptionString($value, $label);
        }
        return $response . "</optgroup>";
    }

    /**
     * Gets the options for the field.
     *
     * @return string
     */
    private function getOptions()
    {
        $options = '';

        if (empty($this->options)) {
            return $options;
        }

        foreach ($this->options as $value => $option) {
            if (false === is_array($option)) {
                $options .= $this->getOptionString($value, $option);
                continue;
            }
            $options .= $this->getOptionGroupString($value, $option);
        }
        return $options;
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
            "<select id='{$this->id}' name='{$this->name}' class='widefat'>" .
            $this->getOptions() .
            "</select>" .
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
