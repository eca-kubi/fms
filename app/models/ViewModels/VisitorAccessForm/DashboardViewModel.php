<?php


namespace ViewModels\VisitorAccessForm;


use ViewModels\ViewModel;
use VisitorAccessForm\DashboardViewFilter;

class DashboardViewModel extends ViewModel
{
    /**
     * @var DashboardViewFilter[]
     */
    public array $view_filters;
    public string $title;

    public function __construct(?array $properties = null)
    {
        parent::__construct('Dashboard', $properties);
    }


    public function initializeRequiredProperties(string $title)
    {
        $this->title = $title;
    }
}
