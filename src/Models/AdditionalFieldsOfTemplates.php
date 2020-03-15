<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Additional fields of templates.
 */
class AdditionalFieldsOfTemplates extends Pivot
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
        'manual_control',
        'rules',
        'active',
        'required',
        'primary',
        'important',
        'priority'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rules' => 'array',
        'active' => 'boolean',
        'manual_control' => 'boolean',
        'required' => 'boolean',
        'primary' => 'boolean',
        'important' => 'boolean',
    ];

    /**
     * Returns an own template of the value of the additional field.
     *
     * @return BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Returns the additional field of the value of the own template.
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
}
