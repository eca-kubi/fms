<?php


namespace ViewModels\VisitorAccessForm;


use SectionsViewModelTrait;
use User;

class SectionCViewModel
{
    use SectionsViewModelTrait;

    public User $site_sponsor;
    public function __construct(User $site_sponsor){
        $this->description_code = SECTION_C_SITE_SPONSORS_APPROVAL;
        $this->title = "Section C: Site Sponsor's Approval";
        $this->site_sponsor = $site_sponsor;
    }
}
