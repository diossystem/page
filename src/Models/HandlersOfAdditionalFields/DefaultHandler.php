<?php

namespace Dios\System\Page\Models\HandlersOfAdditionalFields;

/**
 *
 */
class DefaultHandler
{
    function __construct(AdditionalFieldsOfPages $af, string $property)
    {
        $this->fillFromArray($af->$property);
    }

    public function fillFromArray(array $values)
    {
        $this->values = $values;
    }

    public function toArray(): array
    {
        return $this->values;
    }
}
