<?php


class VisitorAccessFormCollection extends GenericCollection
{
    /**
     * @var VisitorAccessForm[]
     */
    protected array $values;
    public function __construct(VisitorAccessForm ...$visitor_access_forms)
    {
        $this->values = $visitor_access_forms;
    }

    public function setVisitorAccessForms(VisitorAccessForm ...$visitor_access_forms)
    {
        $this->values = $visitor_access_forms;
    }

    /**
     * @return VisitorAccessForm[]
     * */
    public function getVisitorAccessForms()
    {
        return $this->values;
    }

    public static function createFromArrayValues(array $array_values)
    {
        $visitor_access_forms = [];
        foreach ($array_values as $array_value) {
            $visitor_access_forms[] = new VisitorAccessForm($array_value);
        }
        return new self(...$visitor_access_forms);
    }
}
