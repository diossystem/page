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

    protected $propertyOfInstanceValues = 'values';

    /**
     * Type mapping of instance types and their handlers.
     *
     * @var array
     */
    protected $instanceTypeMapping = [
        'map' => \Dios\System\Page\Models\HandlersOfAdditionalFields\Map::class,
    ];

    /**
     * A default instance handler class.
     *
     * @var string|null
     */
    protected $defaultInstanceHandler = \Dios\System\Page\Models\HandlersOfAdditionalFields\DefaultHandler::class;

    /**
     * The cache of instance keys.
     *
     * @var array
     */
    protected static $instanceKeyCache = [];

    /**
     * The source that contains a type.
     * When set second value, then may to use caching of a result of the search
     * instance key.
     *
     * Format that uses the cache: '<first_value>|<second_value>'
     * The first_value is a path to get an instance key.
     * The second_value is a key for the cache.
     * Example: 'af.type|additional_field_id'
     *
     * Format that do not use the cache: '<value>'.
     * The value is a path to get an instance key or it is a property of the current model.
     * Example: 'code_name'
     *
     * @var string
     */
    protected $sourceWithType = 'af.type|additional_field_id';

    /**
     * Instances are handlers of the attributes.
     *
     * @var array
     */
    protected $instances = [
        'values' => '<className>:af.type|additional_field_id',
    ];

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
     * Returns an instance by the given name.
     *
     * @param  string $name
     * @return Instance|null
     */
    public function getInstance(string $name)
    {
        // Источником значения служит поле БД или вызываемый метод get...Attribute
        //
        // Должен возвращать ссылку на сущность или сам объект. Ссылка для того,
        // чтобы можно было манипулировать объектом
    }

    /**
     * Sets a new instance.
     *
     * @param string   $name
     * @param Instance $instance
     */
    public function setInstance(string $name, $instance)
    {
    }

    /**
     * Returns an instance type.
     *
     * @param  bool   $cache
     * @return string|mixed|null
     */
    public function getInstanceType(bool $cache = true)
    {
        /** @var array $sources **/
        $sources = explode('|', $this->sourceWithType, 2);

        if (count($sources) === 2) {
            list($realSource, $linkToSource) = $sources;

            // Gets a key from the static cache
            if ($cache && self::hasCacheInstanceKey($linkToSource)) {
                return self::getCacheOfInstanceKey($linkToSource);
            }

            /** @var string|mixed|null $key **/
            $key = $this->getInstanceKey($realSource);

            // Caches using $linkToSource
            if ($cache) {
                self::addCacheOfInstanceKey($linkToSource, $key);
            }

            return $key;
        }

        return isset($sources[0])
            ? $this->getInstanceKey($sources[0])
            : null
        ;
    }

    /**
     * Returns instance key from its source.
     *
     * @param  string $source
     * @return mixed|null
     */
    protected function getInstanceKey(string $source)
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
     * Adds a new value of cache of instance keys.
     *
     * @param string|mixed $key
     * @param string|mixed|null $value
     */
    protected static function addCacheOfInstanceKey($key, $value)
    {
        self::$instanceKeyCache[$key] = $value;
    }

    /**
     * Returns an instance key by its key index.
     *
     * @param  string|mixed $key
     * @return string|mixed|null
     */
    public static function getCacheOfInstanceKey($key)
    {
        return self::$instanceKeyCache[$key] ?? null;
    }

    /**
     * Returns true when the instance key is cached.
     *
     * @param  string|mixed $key
     * @return bool
     */
    public static function hasCacheInstanceKey($key): bool
    {
        return key_exists($key, self::$instanceKeyCache);
    }

    /**
     * Returns the current cache of instance keys.
     *
     * @return array
     */
    public static function getCacheOfInstanceKeys(): array
    {
        return self::$instanceKeyCache;
    }

    /**
     * Returns true when the mapping has the instance type.
     *
     * @param  string|mixed $type
     * @return bool
     */
    public function hasInstanceType($type): bool
    {
        return isset($this->instanceTypeMapping[$type]) && class_exists($this->instanceTypeMapping[$type]);
    }

    /**
     * Returns true when the default instance handler exists.
     *
     * @return bool
     */
    public function hasDefaultInstanceHandler(): bool
    {
        return is_string($this->defaultInstanceHandler) && class_exists($this->defaultInstanceHandler);
    }

    public function getDefaultInstanceHandlerClassName()
    {
        return $this->hasDefaultInstanceHandler()
            ? $this->defaultInstanceHandler
            : null;
        ;
    }

    public function getInstanceHandlerClassNameByType($type)
    {
        return $this->hasInstanceType($type)
            ? $this->instanceTypeMapping[$type]
            : null
        ;
    }

    /**
     * Returns a class name of a instance handler by the type.
     *
     * @param  string $type
     * @return string|null
     */
    public function getInstanceHandlerClassNameOrDefaultClassName($type)
    {
        return getInstanceHandlerClassNameByType() ?? $this->getDefaultInstanceHandlerClassName();
    }

    // for one
    public function getInstanceAttribute()
    {
        /** @var string|mixed|null $type **/
        $type = $this->getInstanceType();

        /** @var string|null $className **/
        $className = $this->getInstanceHandlerClassNameOrDefault($type);

        if (! $className) {
            return null;
        }

        // TODO проверить существующий экземпляр и вернуть его
        // иначе создать, присвоить и вернуть.

        $instance = new $className($this, $this->propertyOfInstanceValues);

        return $instance;
        // TODO возвращает преобразованный экземпляр данных из поля values.
        // При этом должен быть получен тип поля и экземпляр должен создаваться один
        // раз, т.к. обработка может быть долгой. Если экземпляр уже создан,
        // то нужно просто его возвращать
        // ->values => array
        // ->instance => object by type
        // Типы поля также желательно как-то кэшировать, чтобы не было дополнительных
        // запросов к БД.
        // Получается обработка данных должна происходить либо при извлечение данных
        // из БД, либо на момент обращения к свойству.
        // Должен расширять или быть совместим с Casting fields
        //
        // TODO должен расширяться трейтом.
        // Может управляться, типа использование кэша классов или нет
        // (каждому классу определенный тип),
        // использование кэша типов или нет (каждому типу определенный ID из определенного
        // поля). Причем может как статическим атрибутом быть, так и храниться в файле

        // Если уже один раз поле возвращалось за запрос, то возвращаем его экземпляр
        // if ($this->hasInstanceCache($this)) {
        //     return $this->instanceCache[$this->id];
        // }

        // Если используется какой-то тип, то нужно определить класс и передать
        // в него значения и вернуть экземпляр класса и закэшировать его
        // $this->getInstanceType() -> любая обработка по получению типа,
        // т.к. тип может храниться как в текущей модели, так и в любой другой
        // связанной модели или вообще вычисляться из хранимых полей,
        // т.е. быть внутри значения массива или определяться по умолчанию.
        // Но по умолчанию может храниться в поле instance_type (или любом другом),
        // т.е. нет значения по умолчанию, и может быть хоть type.
        // $instanceType = 'type' or 'af.type'
        //
        // if ($this->additionalField->type != 'custom') {
        //     // Определяем класс по его типу.
        //     // Хранить пути к классам можем как в конфиге (не самый лучший путь),
        //     // в Модели дополнительного поля,
        //     // в текущей модели (логичнее, т.к. тип может храниться вместе с данными).
        //     // Должно быть соотвествие поля Тип и его значению.
        // }
        //
        // return new Custom($this);
    }

    /**
     * Returns a class name of the current type.
     *
     * @return string|null
     */
    public function getInstanceClass()
    {
        return static::getInstanceClassByType($type);
    }

    public static function getInstanceClassByType(string $type)
    {

    }

    // public function getInstance($cache = true)
    // {
    //     // TODO возвращает экземлпяр вызываемого поля.
    //     // Если включено кэширование и данные уже вызывались, то возвращает их
    //     // снова, если до этого они не были изменены.
    // }
}
