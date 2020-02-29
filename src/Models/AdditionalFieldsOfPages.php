<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalFieldsOfPages extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'additional_field_id', 'values'
    ];

    protected $casts = [
        'values' => 'array',
    ];

    public function additionalField()
    {
        return $this->belongsTo(AdditionalField::class);
    }

    public function af()
    {
        return $this->additionalField();
    }
}
