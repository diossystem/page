<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
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

    protected $dates = ['published_at'];

    protected $casts = [
        'important' => 'boolean',
    ];

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function additionalFields()
    {
        return $this->hasMany(AdditionalFieldsOfPages::class);
    }

    public function afs()
    {
        return $this->additionalFields();
    }

    public function scopeState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', 'like', $slug);
    }

    public function scopeLink($query, $link)
    {
        return $query->where('link', 'like', $link);
    }

    public function scopeActive($query)
    {
        return $query->state('published');
    }
}
