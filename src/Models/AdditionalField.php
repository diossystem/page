<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalField extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code_name', 'title', 'description', 'type'
    ];

    public function pages()
    {
        return $this->hasMany(AdditionalFieldsOfPages::class);
    }

    public function templates()
    {
        return $this->hasMany(AdditionalFieldsOfTemplates::class);
    }

    public function scopeName($query, $name)
    {
        return $query->where('code_name', $name);
    }

    public function scopeActive($query, $state = true)
    {
        return $query->where('active', $state);
    }
}
