<?php

namespace Dios\System\Page\Models\HandlersOfAdditionalFields;

use Exception;
use Dios\System\Page\Models\AdditionalFieldsOfPages;
use Illuminate\Database\Eloquent\Model;
use Dios\System\Page\MulticastingInterfaces\InstanceFromModel;

/**
 * Keeps data of a map.
 */
class Map implements InstanceFromModel
{
    protected $title;

    protected $address;

    protected $script;

    protected $url;

    protected $image;

    function __construct(Model $af, string $property)
    {
        if (! ($af instanceof AdditionalFieldsOfPages)) {
            throw new Exception('The given instance is invalid.');
        }

        $this->fillFromArray($af->$property ?? []);
    }

    /**
     * Fills an instance of the class by with values.
     *
     * @param  array  $values
     */
    public function fillFromArray(array $values)
    {
        $this->title = $values['title'] ?? 'Map';
        $this->address = $values['address'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->phones = isset($values['phones']) && is_array($values['phones']) ? $values['phones'] : [];
        $this->script = $values['script'] ?? null;
        $this->url = $values['url'] ?? null;
        $this->image = $values['image'] ?? null;
    }

    /**
     * Returns a title of the map.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns an address of the point.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns a script to show map.
     *
     * @return string|null
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Returns an URL to the map.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns an URL to an image of the map.
     *
     * @return string|null
     */
    public function getUrlToImage()
    {
        return $this->image;
    }

    /**
     * Returns a phone number of a company.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Returns phone numbers of a company.
     *
     * @return array
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Returns values of the instance in the form of the array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'address' => $this->address,
            'phone' => $this->phone,
            'phones' => $this->phones,
            'script' => $this->script,
            'url' => $this->url,
            'image' => $this->image,
        ];
    }
}
