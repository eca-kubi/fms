<?php


namespace ViewModels\VisitorAccessForm;


use SectionsViewModelTrait;
use User;
use VisitorAccessForm\Approval;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

class SectionDViewModel
{
    use SectionsViewModelTrait;
    public ?Approval $approval = null;

    public function __construct(?Approval $approval){
        $this->description_code = SECTION_D_SITE_ACCESS_APPROVAL;
        $this->title = "Section D: Site Access Approval";
        $this->approval = $approval;
    }
}
