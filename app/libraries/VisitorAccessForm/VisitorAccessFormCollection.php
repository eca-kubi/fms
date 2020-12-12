<?php

namespace VisitorAccessForm;
use GenericCollection;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

class VisitorAccessFormCollection extends GenericCollection
{
    /**
     * @var VisitorAccessFormEntity[]
     */
    protected array $values;
    public function __construct(VisitorAccessFormEntity ...$visitor_access_forms)
    {
        $this->values = $visitor_access_forms;
    }

    public function setVisitorAccessForms(VisitorAccessFormEntity ...$visitor_access_forms)
    {
        $this->values = $visitor_access_forms;
    }

    /**
     * @return VisitorAccessFormEntity[]
     * */
    public function getVisitorAccessForms()
    {
        return $this->values;
    }

    public static function createFromArrayValues(array $array_values) : self
    {
        $visitor_access_forms = [];
        foreach ($array_values as $array_value) {
            $visitor_access_forms[] = new VisitorAccessFormEntity($array_value);
        }
        return new self(...$visitor_access_forms);
    }
}
