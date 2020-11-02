<?php


class GenericEntity implements JsonSerializable
{
    use InitializeProperties;

    public function __construct(?array $properties = null)
    {
        $this->initialize($properties);
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function findEntity(string $className, int $id): ?object
    {
        // finds and returns an entity object based on $className
        // and primary key in $id
        return $this;
    }
}
