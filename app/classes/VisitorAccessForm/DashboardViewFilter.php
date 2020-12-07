<?php


namespace VisitorAccessForm;


use GenericEntity;

class DashboardViewFilter extends GenericEntity
{
    public VisitorAccessFormCollection $form_collection;
    public string $name;
    public string $color;
    public string $id;

    public function __construct(VisitorAccessFormCollection $form_collection, string $name, string $color, string $id)
    {
        parent::__construct();
        $this->form_collection = $form_collection;
        $this->name = $name;
        $this->color = $color;
        $this->id = $id;
    }
}
