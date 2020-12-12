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
}
