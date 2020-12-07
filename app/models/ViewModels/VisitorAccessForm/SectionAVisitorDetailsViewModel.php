<?php

namespace ViewModels\VisitorAccessForm;


use SectionsViewModelTrait;
use User;

class SectionAVisitorDetailsViewModel {
    use SectionsViewModelTrait;
    /**
     * @var User[]
     */
    public array $department_approvers = [];
    public bool $display_form_default_submit_button = false;

    public function __construct()
    {
        $this->title =  'Section A : Visitor Details';
        $this->description_code = SECTION_A_VISITOR_DETAILS;
    }

}

