<?php


namespace ViewModels\VisitorAccessForm;


use SectionsViewModelTrait;
use User;
use ValidationErrorCollection;
use ViewModels\ViewModel;
use VisitorAccessForm\AccessLevelCollection;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;
use VisitorAccessForm\ValidationTrait;

class SectionsViewModel extends ViewModel
{
    use ValidationTrait;

    public string $description_code = 'sections';
    public VisitorAccessFormEntity $visitor_access_form;
    public SectionAVisitorDetailsViewModel $section_a;
    public SectionBAccessLevelViewModel $section_b;
    public SectionCViewModel $section_c;
    public SectionDViewModel $section_d;

    public function __construct(VisitorAccessFormEntity $visitor_access_form, ?array $properties = null)
    {
        parent::__construct('Sections', $properties);
        $this->description_code = 'sections';
        $this->errors = new ValidationErrorCollection(...[]);
        $this->visitor_access_form = $visitor_access_form;
    }

    /**
     * @return SectionBAccessLevelViewModel
     */
    public function getSectionB(): SectionBAccessLevelViewModel
    {
        return $this->section_b;
    }

    /**
     * @return SectionAVisitorDetailsViewModel
     */
    public function getSectionA(): SectionAVisitorDetailsViewModel
    {
        return $this->section_a;
    }

    public function initializeRequiredProperties(string $title)
    {
       $this->title = $title;
    }
}
