<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Keeps data of a template.
 *
 * @property int $id An ID of the template.
 * @property string $title A user title of the template.
 * @property string $description A description of the template.
 * @property string $code_name A code name of the template.
 * @property int|null $parent_id An ID of a parent of the template.
 * @property bool $active A state of the template.
 * @property int $priority A priority of the template.
 * @property-read Collection $children Child templates of the template.
 * @property-read Template|null $parent A parent template of the template.
 * @property-read Collection $additionalFields Additional fields of the templates.
 * @property-read Collection $afs Additional fields of the templates.
 * @property-read PageCollection $pages Pages of the template.
 */
class Template extends Model
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
        'title',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Returns child templates.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Returns a parent of the template.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Returns additional fields of the template.
     *
     * @return BelongsToMany
     */
    public function additionalFields(): BelongsToMany
    {
        return $this
            ->belongsToMany(AdditionalField::class)
            ->using(AdditionalFieldsOfTemplates::class)
            ->withPivot([
                'manual_control',
                'rules',
                'active',
                'required',
                'primary',
                'important',
                'priority'
            ])
        ;
    }

    /**
     * The alias of the additionalFields function.
     *
     * @return BelongsToMany
     */
    public function afs(): BelongsToMany
    {
        return $this->additionalFields();
    }

    /**
     * Returns pages of the template.
     *
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    /**
     * Returns templates that have the given ID of parent.
     *
     * @param  Builder $query
     * @param  int     $parentId
     * @return Builder
     */
    public function scopeParentId(Builder $query, int $parentId = 0): Builder
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Returns templates that have the active state or another given state.
     *
     * @param  Builder $query
     * @param  bool    $state
     * @return Builder
     */
    public function scopeActive(Builder $query, bool $state = true): Builder
    {
        return $query->where('active', $state);
    }

    /**
     * Returns templates that have the given names.
     *
     * @param  Builder $query
     * @param  string  ...$name
     * @return Builder
     */
    public function scopeName(Builder $query, string ...$name): Builder
    {
        return $query->whereIn('code_name', $name);
    }
}
