<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public $timestamps = false;

    protected $fillable = ['code_name', 'title', 'description'];

    protected $casts = [
        'options' => 'array',
        'active' => 'boolean'
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function additionalFields()
    {
        return $this->hasMany(AdditionalFieldsOfTemplates::class);
    }

    public function afs()
    {
        return $this->additionalFields();
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function scopeParent($query, $parent_id = 0)
    {
        return $query->where('parent_id', $parent_id);
    }

    public function scopeActive($query, $state = true)
    {
        return $query->where('active', $state);
    }

    public function scopeName($query, $name)
    {
        if (is_array($name)) {
            return $query->whereIn('code_name', $name);
        }

        return $query->where('code_name', $name);
    }

    public function getNameAttribute()
    {
        return $this->attributes['code_name'];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['code_name'] = str_slug($value);
    }
}
