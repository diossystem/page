<?php

namespace Dios\System\Page\Models;

use DateTime;
use Dios\System\Page\Enums\PageState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Keeps data of a page.
 *
 * @property int $id An ID of the page
 * @property string $title A page title
 * @property string|null $subtitle A page subtitle
 * @property string|null $content A page content
 * @property string|null $discription A page description
 * @property string|null $description_tag A description for the meta tag of the page head
 * @property string|null $keywords_tag Keywords for the meta tag of the page head
 * @property string|null $slug A slug of the page or a part of the link of the page
 * @property string|null $link A full link to the page
 * @property string $state A state of the page
 * @property Carbon $published_at A date of the publication
 * @property int $priority
 * @property bool $important
 * @property int|null $template_id
 * @property int $author_id
 * @property int|null $editor_id
 * @property int|null $parent_id
 * @property-read Template|null $template An instance of the related template
 * @property-read Collection|AdditionalField[] $additionalFields Additional fields of the page
 * @property-read Collection|AdditionalField[] $afs Additional fields of the page
 * @property-read Page|null $parent A parent page of the page
 * @property-read PageCollection $children Child pages of the page
 * @method Builder state(string $state) Returns pages that have the given state.
 * @method Builder slug(string $slug) Returns pages that have the given slug.
 * @method Builder link(string $link) Returns pages that have the given link.
 * @method Builder active(bool $active) Returns active pages.
 * @method Builder activeTemplate() Returns pages that have active templates.
 * @method Builder published(DateTime $currentDate = null) Returns published pages.
 * @method Builder seen(DateTime $currentDate = null) Returns pages that are allowed to show.
 */
class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'priority',
        'title',
        'subtitle',
        'content',
        'description',
        'description_tag',
        'keywords_tag',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
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
     * Set 'false' to $active to get inactive pages.
     *
     * @param  Builder $query
     * @param  bool    $active
     * @return Builder
     */
    public function scopeActive(Builder $query, bool $active = true): Builder
    {
        return $active
            ? $query->state(PageState::PUBLISHED)
            : $query->where('state', '<>', PageState::PUBLISHED)
        ;
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
     * WARNING: The NOW() function do not work with SQLite.
     * Set $currentDate with an instance of DateTime, such as "new DateTime('now')".
     *
     * @param  Builder  $query
     * @param  DateTime $currentDate
     * @return Builder
     */
    public function scopePublished(Builder $query, DateTime $currentDate = null): Builder
    {
        return $currentDate
            ? $query->whereRaw('published_at <= ?', $currentDate->format('Y-m-d H:i:s'))
            : $query->whereRaw('published_at <= NOW()')
        ;
    }

    /**
     * Returns pages that are allowed to show.
     *
     * @param  DateTime $currentDate
     * @return Builder
     */
    public function scopeSeen(Builder $query, DateTime $currentDate = null): Builder
    {
        return $query
            ->activeTemplate()
            ->active()
            ->published($currentDate)
        ;
    }

    /**
     * Returns a collection with pages.
     *
     * @param  array|Page[] $models
     * @return PageCollection
     */
    public function newCollection(array $models = []): PageCollection
    {
        return new PageCollection($models);
    }
}
