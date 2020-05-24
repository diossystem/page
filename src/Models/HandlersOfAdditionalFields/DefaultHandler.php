<?php

namespace Dios\System\Page\Models\HandlersOfAdditionalFields;

use Dios\System\Multicasting\Multicasting\SimpleArrayEntity;

/**
 *
 */
class DefaultHandler implements SimpleArrayEntity
{
    function __construct(array $values = null)
    {
        $this->fillFromArray($values ?? []);
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
