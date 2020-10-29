<?php


class VisitorCollection extends GenericCollection
{
    /**
     * @var Visitor[]
     */
    protected array $values;
    public function __construct(Visitor ...$visitors)
    {
        $this->values = $visitors;
    }

    public function setVisitors(Visitor ...$visitors)
    {
        $this->values = $visitors;
    }

    /**
     * @return Visitor[]
     * */
    public function getVisitors()
    {
        return $this->values;
    }

    public static function createFromArrayValues(array $array_values)
    {
        $visitors = [];
        foreach ($array_values as $array_value) {
            $visitors[] = new Visitor($array_value);
        }
        return new self(...$visitors);
    }
}
