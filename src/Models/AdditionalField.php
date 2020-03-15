<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdditionalField extends Model
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
        'code_name',
        'title',
        'description',
        'type'
    ];

    /**
     * Returns pages of the additional field.
     *
     * @return BelongsToMany
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class)
            ->using(AdditionalFieldsOfPages::class)
            ->withPivot('values')
        ;
    }

    /**
     * Returns templates of the additional field.
     *
     * @return BelongsToMany
     */
    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class)
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
     * Returns additional fields that have the given names.
     *
     * @param  Builder $query
     * @param  string  ...$name
     * @return Builder
     */
    public function scopeName(Builder $query, string ...$name): Builder
    {
        return $query->whereIn('code_name', $name);
    }

    /**
     * Returns additional field that have the active state or another given state.
     *
     * @param  Builder $query
     * @param  bool    $state
     * @return Builder
     */
    public function scopeActive(Builder $query, bool $state = true): Builder
    {
        return $query->where('active', $state);
    }
}
