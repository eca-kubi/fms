<?php

namespace ViewModels;

use InitializeProperties;

abstract class ViewModel
{
    use InitializeProperties;

    public string $title;
    /**
     * @var bool Will be used to toggle the display of form controls
     */
    public bool $display_form_controls = false;

    public function __construct(string $title, ?array $properties = null)
    {
        $this->initialize($properties);
        $this->initializeRequiredProperties($title);
    }

    public abstract function initializeRequiredProperties(string $title);
}
