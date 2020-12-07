<?php


namespace ViewModels\VisitorAccessForm;


use DbModels\VisitorAccessForm\AccessLevelDbModel;
use DbModels\VisitorAccessForm\VisitorAccessFormDbModel;
use ViewModels\ViewModel;
use VisitorAccessForm\AccessLevelCollection;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

class PrintViewModel extends ViewModel
{
    public AccessLevelCollection $access_levels;
    public VisitorAccessFormEntity $visitor_access_form;
    public bool $print = false;

    public function __construct(int $visitor_access_form_id, string $title = 'Visitor Access Form', ?array $properties = null)
    {
        parent::__construct($title, $properties);
        $this->access_levels = AccessLevelDbModel::getWithFormId($visitor_access_form_id);
        $this->visitor_access_form = VisitorAccessFormDbModel::getEntitySingle($visitor_access_form_id);
        $this->title = $title . ' (' . $this->visitor_access_form->document_id .')';
    }

    public function initializeRequiredProperties(string $title)
    {
        $this->title = $title;
    }
}
