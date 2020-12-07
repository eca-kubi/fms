<?php


trait InitializeProperties
{
    public function initialize(?array $properties){
        if (is_array($properties))
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property))
            $this->{$property} = $value;
        }
        return $this;
    }

    public function setProperties(array $properties)
    {
        $this->initialize($properties);
        return $this;
    }
}
