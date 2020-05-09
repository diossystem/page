<?php

namespace Dios\System\Page\Models\HandlersOfAdditionalFields;

use Dios\System\Page\Models\AdditionalFieldsOfPages;

/**
 *
 */
class Map
{
    protected $title;

    protected $address;

    protected $script;

    protected $link;

    protected $url;

    function __construct(AdditionalFieldsOfPages $af, string $property)
    {
        $this->fillFromArray($af->$property ?? []);
    }

    public function fillFromArray(array $values)
    {
        // TODO должен содержать все переменные или значения по умолчанию
        $this->values = $values;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'address' => $this->address,
            'phone' => $this->phone,
            'phones' => $this->phones,
            'script' => $this->script,
            'link' => $this->link,
            'image' => $this->image,
        ];
    }
}
