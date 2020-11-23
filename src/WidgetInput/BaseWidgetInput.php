<?php

namespace TidesToday\TideTimes\WidgetInput;

/**
 * Class BaseWidgetInput
 * @package TidesToday\TideTimes\WidgetInput
 */
abstract class BaseWidgetInput
{
    /** @var string $id */
    public $id;

    /** @var string $name */
    public $name;

    /** @var null|string|int $value */
    public $value;

    /** @var null|string $label */
    public $label;

    /** @var null|string $description */
    public $description;

    /** @var null|string $placeholder */
    public $placeholder;

    /**
     * BaseWidgetInput constructor.
     * @param $id
     * @param $name
     * @param null $value
     * @param null $label
     * @param null $description
     * @param null $placeholder
     */
    public function __construct($id, $name, $value = null, $label = null, $description = null, $placeholder = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->description = $description;
        $this->placeholder = $placeholder;
    }

    /**
     * @return string
     */
    public abstract function render();
}
