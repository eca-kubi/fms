<?php


namespace VisitorAccessForm;


use GenericCollection;

class DashboardViewFilterCollection extends GenericCollection
{

    /**
     * @var DashboardViewFilter[]
     */
    protected array $values;
    public function __construct(DashboardViewFilter ...$view_filters)
    {
        $this->values = $view_filters;
    }

    public function setDashboardViewFilters(DashboardViewFilter ...$view_filters)
    {
        $this->values = $view_filters;
    }

    /**
     * @return DashboardViewFilter[]
     * */
    public function getDashboardViewFilters()
    {
        return $this->values;
    }

    public static function createFromArrayValues(array $array_values)
    {
        $view_filters = [];
        foreach ($array_values as $array_value) {
            $view_filters[] = new DashboardViewFilter($array_value['form_collection'], $array_value['name'], $array_value['color']);
        }
        return new self(...$view_filters);
    }
}
