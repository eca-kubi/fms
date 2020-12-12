<?php


trait SectionsViewModelTrait
{
    public string $title = 'Section';
    public string $description_code = 'sections';
    private static int $html_form_counter = 0;
    private static string $html_form_id = '';

    public function setHtmlFormId () {
        self::$html_form_id =  'form_'. $this->description_code .'_' . self::$html_form_counter++;
    }

    /**
     * @return string
     */
    public static function getHtmlFormId(): string
    {
        return self::$html_form_id;
    }
}
