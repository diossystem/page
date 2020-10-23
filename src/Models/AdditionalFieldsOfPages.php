<?php

namespace Dios\System\Page\Models;

use Dios\System\Multicasting\AttributeMulticasting;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Additional fields of pages.
 *
 * @property int $additional_field_id An ID of the additional field.
 * @property array|null $values Some values of the additional field.
 * @property-read MulticastingEntity|null $instance An instance of the current type of the AF.
 */
class AdditionalFieldsOfPages extends Pivot
{
    use AttributeMulticasting;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'additional_field_id',
        'values'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'values' => 'array',
    ];

    /**
     * The property that contains values to an entity.
     *
     * @var string
     */
    protected $propertyOfEntityValues = 'values';

    /**
     * Type mapping of entity types and their handlers.
     *
     * @var array
     */
    protected $entityTypeMapping = [
        'map' => \Dios\System\Page\Models\HandlersOfAdditionalFields\Map::class,
    ];

    /**
     * A default entity handler class.
     *
     * @var string|null
     */
    protected $defaultEntityHandler = \Dios\System\Page\Models\HandlersOfAdditionalFields\DefaultHandler::class;

    /**
     * The source that contains an entity type.
     * When set second value, then may to use caching of a result of the search
     * entity key.
     *
     * Format that uses the cache: '<first_value>|<second_value>'
     * The first_value is a path to get an entity key.
     * The second_value is a key for the cache.
     * Example: 'af.type|additional_field_id'
     *
     * Format that do not use the cache: '<value>'.
     * The value is a path to get an entity key or it is a property of the current model.
     * Example: 'code_name'
     *
     * @var string
     */
    protected $sourceWithEntityType = 'af.type|additional_field_id';

    /**
     * The instance type of entities.
     *
     * @var string
     */
    protected $interfaceType = 'instance_from_model';

    /**
     * Returns an additional field of the page.
     *
     * @return BelongsTo
     */
    public function additionalField(): BelongsTo
    {
        return $this->belongsTo(AdditionalField::class);
    }

    /**
     * The alias of the additionalField function.
     *
     * @return BelongsTo
     */
    public function af(): BelongsTo
    {
        return $this->additionalField();
    }

    /**
     * Returns an own page.
     *
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Returns an instance of the instance of the model from the attribute.
     *
     * @return EntityHandlerInterface|null
     */
    public function getInstanceAttribute()
    {
        return $this->getInstance();
    }

    /**
     * Sets values to the instance or the attribute.
     *
     * @param array $values
     */
    public function setInstanceAttribute(array $values)
    {
        $instance = $this->getInstance();

        if ($instance) {
            $this->fillFromArray($values);
        } else {
            $this->{$this->propertyOfEntityValues} = $values;
        }
    }
}
