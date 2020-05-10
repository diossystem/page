<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Additional fields of pages.
 */
class AdditionalFieldsOfPages extends Pivot
{
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
     * The cache of entity keys.
     *
     * @var array
     */
    protected static $entityKeyCache = [];

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
     * An instance of the current entity.
     *
     * @var EntityHandlerInterface|null
     */
    protected $instanceOfEntity;

    // /**
    //  * Instances are handlers of the attributes.
    //  *
    //  * @var array
    //  */
    // protected $instances = [
    //     'values' => '<className>:af.type|additional_field_id',
    // ];

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

    // /**
    //  * Sets a new instance.
    //  *
    //  * @param string   $name
    //  * @param Instance $instance
    //  */
    // public function setInstance(string $name, $instance)
    // {
    // }

    /**
     * Returns an entity type.
     *
     * @param  bool   $cache
     * @return string|mixed|null
     */
    public function getEntityType(bool $cache = true)
    {
        /** @var array $sources **/
        $sources = explode('|', $this->sourceWithEntityType, 2);

        if (count($sources) === 2) {
            list($realSource, $linkToSource) = $sources;

            // Gets a key from the static cache
            if ($cache && self::hasCacheEntityKey($linkToSource)) {
                return self::getCacheOfEntityKey($linkToSource);
            }

            /** @var string|mixed|null $key **/
            $key = $this->getEntityKey($realSource);

            // Caches using $linkToSource
            if ($cache) {
                self::addCacheOfEntityKey($linkToSource, $key);
            }

            return $key;
        }

        return isset($sources[0])
            ? $this->getEntityKey($sources[0])
            : null
        ;
    }

    /**
     * Returns an entity key from its source.
     *
     * @param  string $source
     * @return mixed|null
     */
    protected function getEntityKey(string $source)
    {
        /** @var array $segments Segments to a value ***/
        $segments = explode('.', $source);

        // Uses the current model as the start value
        /** @var Model|string|null **/
        $value = $this;

        // Finds a key using all segments. They may be in related models.
        foreach ($segments as $segment) {
            if (isset($value->$segment)) {
                $value = $value->$segment;
            } else {
                $value = null;
                break;
            }
        }

        return is_scalar($value)
            ? $value
            : null
        ;
    }

    /**
     * Adds a new value of cache of entity keys.
     *
     * @param string|mixed $key
     * @param string|mixed|null $value
     */
    protected static function addCacheOfEntityKey($key, $value)
    {
        self::$entityKeyCache[$key] = $value;
    }

    /**
     * Returns an entity key by its key index.
     *
     * @param  string|mixed $key
     * @return string|mixed|null
     */
    public static function getCacheOfEntityKey($key)
    {
        return self::$entityKeyCache[$key] ?? null;
    }

    /**
     * Returns true when the entity key is cached.
     *
     * @param  string|mixed $key
     * @return bool
     */
    public static function hasCacheEntityKey($key): bool
    {
        return key_exists($key, self::$entityKeyCache);
    }

    /**
     * Returns the current cache of entity keys.
     *
     * @return array
     */
    public static function getCacheOfEntityKeys(): array
    {
        return self::$entityKeyCache;
    }

    /**
     * Returns true when the mapping has the entity type.
     *
     * @param  string|mixed $type
     * @return bool
     */
    public function hasEntityType($type): bool
    {
        return isset($this->entityTypeMapping[$type]) && class_exists($this->entityTypeMapping[$type]);
    }

    /**
     * Returns true when the default entity handler exists.
     *
     * @return bool
     */
    public function hasDefaultEntityHandler(): bool
    {
        return is_string($this->defaultEntityHandler) && class_exists($this->defaultEntityHandler);
    }

    /**
     * Returns the default entity handler class name.
     *
     * @return string|null
     */
    public function getDefaultEntityHandlerClassName()
    {
        return $this->hasDefaultEntityHandler()
            ? $this->defaultEntityHandler
            : null;
        ;
    }

    /**
     * Returns an entity handler class name by its type.
     *
     * @param  string $type
     * @return string|null
     */
    public function getEntityHandlerClassNameByType($type)
    {
        return $this->hasEntityType($type)
            ? $this->entityTypeMapping[$type]
            : null
        ;
    }

    /**
     * Returns a class name of an entity handler by the type.
     *
     * @param  string $type
     * @return string|null
     */
    public function getEntityHandlerClassNameOrDefaultClassName($type)
    {
        return $this->getEntityHandlerClassNameByType($type) ?? $this->getDefaultEntityHandlerClassName();
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
     * Returns an instance of the current instance of the model.
     * If the instance has not been initialized yet, this will be done.
     *
     * @return EntityHandlerInterface|null
     */
    public function getInstance()
    {
        if (! isset($this->instanceOfEntity)) {
            $this->instanceOfEntity = $this->makeInstanceOfEntity();
        }

        return $this->instanceOfEntity;
    }

    /**
     * Initializes an instance of the current entity and returns it.
     *
     * @return EntityHandlerInterface|null
     */
    public function makeInstanceOfEntity()
    {
        /** @var string|mixed|null $type **/
        $type = $this->getEntityType();

        /** @var string|null $className **/
        $className = $this->getEntityHandlerClassNameOrDefaultClassName($type);

        if (! $className) {
            return null;
        }

        return new $className($this, $this->propertyOfEntityValues);
    }
}
