<?php


namespace ViewModels;


class StartPageViewModel extends ViewModel
{
    public ?string $sub_page = null;

    public function __construct(string $title, ?array $properties = null)
    {
        parent::__construct($title, $properties);
    }

    public function initializeRequiredProperties(string $title)
    {
        $this->title  = $title;
    }
}
