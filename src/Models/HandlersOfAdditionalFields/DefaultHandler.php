<?php

namespace Dios\System\Page\Models\HandlersOfAdditionalFields;

use Dios\System\Multicasting\Interfaces\SimpleArrayEntity;

/**
 * The default handler of pages.
 */
class DefaultHandler implements SimpleArrayEntity
{
    /**
     * Initializes an instance of the class.
     */
    function __construct(array $values = null)
    {
        $this->fillFromArray($values ?? []);
    }

    /**
     * Fills the instance with the given array.
     *
     * @param array $values
     * @return void
     */
    public function fillFromArray(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns original values of the instance in the form of the array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
