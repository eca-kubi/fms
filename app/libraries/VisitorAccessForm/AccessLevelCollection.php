<?php


namespace VisitorAccessForm;

use GenericCollection;
use VisitorAccessForm\Entities\AccessLevel;

class AccessLevelCollection extends GenericCollection
{

    protected array $values;
    public function __construct(AccessLevel ...$access_levels)
    {
        $this->values = $access_levels;
    }

    public function setAccessLevels(AccessLevel ...$access_levels)
    {
        $this->values = $access_levels;
    }

    /**
     * @return AccessLevel[]
     * */
    public function getAccessLevels()
    {
        return $this->values;
    }

    public function filterCollection(callable $callback)
    {
        return array_filter($this->values, $callback );
    }

    public static function createFromArrayValues(array $array_values) : self
    {
        $access_levels = [];
        foreach ($array_values as $array_value) {
            $access_levels[] = new AccessLevel($array_value);
        }
        return new self(...$access_levels);
    }
}
