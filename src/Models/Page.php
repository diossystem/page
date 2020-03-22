<?php

namespace Dios\System\Page\Models;

use DateTime;
use Dios\System\Page\Enums\PageState;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'published_at',
        'priority',
        'parent_id',
        'important',
        'title',
        'state',
        'subtitle',
        'content',
        'description',
        'description_tag',
        'keywords_tag',
        'author_id',
        'editor_id',
        'template_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'important' => 'boolean',
    ];

    /**
     * Returns a parent of the page.
     *
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * Returns children of the page.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Returns a template of the page.
     *
     * @return BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Returns additional fields of the page.
     *
     * @return HasMany
     */
    public function additionalFields(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalField::class)
            ->using(AdditionalFieldsOfPages::class)
            ->withPivot('values')
        ;
    }

    /**
     * The alias of the additionalFields function.
     *
     * @return HasMany
     */
    public function afs(): BelongsToMany
    {
        return $this->additionalFields();
    }

    /**
     * Returns pages that have the given state.
     *
     * @param  Builder $query
     * @param  string  $state
     * @return Builder
     */
    public function scopeState(Builder $query, string $state): Builder
    {
        return $query->where('state', $state);
    }

    /**
     * Returns pages that have the given slug.
     *
     * @param  Builder $query
     * @param  string  $slug
     * @return Builder
     */
    public function scopeSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', 'like', $slug);
    }

    /**
     * Returns pages that have the given link.
     *
     * @param  Builder $query
     * @param  string  $link
     * @return Builder
     */
    public function scopeLink(Builder $query, string $link): Builder
    {
        return $query->where('link', 'like', $link);
    }

    /**
     * Returns active pages.
     * An active page is a page whose the state is PUBLISHED.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->state(PageState::PUBLISHED);
    }

    /**
     * Returns pages that have active templates.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeActiveTemplate(Builder $query): Builder
    {
        return $query->whereHas('template', function ($query) {
            $query->active();
        });
    }

    /**
     * Returns published pages.
     * A published page is a page whose a date of publication greater or equal
     * than the current date.
     *
     * @param  Builder  $query
     * @param  DateTime $currentDate
     * @return Builder
     */
    public function scopePublished(Builder $query, DateTime $currentDate = null): Builder
    {
        return $currentDate
            ? $query->whereRaw('published_at < ?', $currentDate->format('Y-m-d H:i:s'))
            : $query->whereRaw('published_at < NOW()')
        ;
    }

    /**
     * Returns pages that are allowed to show.
     *
     * @return Builder
     */
    public function scopeSeen(Builder $query): Builder
    {
        return $query
            ->activeTemplate()
            ->active()
            ->published()
        ;
    }
}
