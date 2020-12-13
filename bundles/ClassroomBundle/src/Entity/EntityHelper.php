<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Entity;

/**
 * Trait EntityHelper contains some abstract specific logic to help with classroom data
 */
trait EntityHelper
{
    /**
     * Initialize object with properties from array
     *
     * @param array $values Array with properties
     * @param bool  $hard   Set even sacred properties
     */
    public function hydrate(array $values = [], bool $hard = false)
    {
        $sacredProperties = ['id', 'createdAt'];
        foreach ($values as $property => $value) {
            if (!$hard && in_array($property, $sacredProperties)) {
                continue;
            }
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
