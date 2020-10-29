<?php


trait InitializeProperties
{
    public function initialize(?array $properties){
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property))
            $this->{$property} = $value;
        }
    }
}
