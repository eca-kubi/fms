<?php


namespace ViewModels\VisitorAccessForm;


use SectionsViewModelTrait;
use VisitorAccessForm\AccessLevelCollection;

class SectionBAccessLevelViewModel
{
    use SectionsViewModelTrait;

    public ?AccessLevelCollection $access_levels = null;

    public function __construct(AccessLevelCollection $access_levels)
    {
        $this->description_code = SECTION_B_ACCESS_LEVEL;
        $this->access_levels = $access_levels;
        $this->title = 'Section B : Access Level';
    }
}
