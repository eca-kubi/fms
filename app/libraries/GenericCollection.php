<?php


abstract class GenericCollection implements IteratorAggregate, iGenericCollection, JsonSerializable
{
    protected  array $values;

    public function toArray() : array {
        return $this->values;
    }

    public function getIterator() {
        return new ArrayIterator($this->values);
    }

    public function jsonSerialize()
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = $value->jsonSerialize();
        }
        return $values;
    }
}
