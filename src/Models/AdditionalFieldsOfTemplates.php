<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalFieldsOfTemplates extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'additional_field_id', 'manual_control', 'rules', 'active', 'required',
        'primary', 'important', 'priority'
    ];

    protected $casts = [
        'rules' => 'array',
        'active' => 'boolean',
        'manual_control' => 'boolean',
        'required' => 'boolean',
        'primary' => 'boolean',
        'important' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function additionalField()
    {
        return $this->belongsTo(AdditionalField::class);
    }

    public function af()
    {
        return $this->additionalField();
    }
}
