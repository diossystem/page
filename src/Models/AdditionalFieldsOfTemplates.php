<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Additional fields of templates.
 *
 * @property int $additional_field_id An ID of the additional filed.
 * @property bool $manual_control A state of the manual control.
 * @property array $rules Rules of the additional field.
 * @property bool $active A state of activity of the additional field.
 * @property bool $required A state of need of the additional field.
 * @property bool $primary A state of primary of the additional field.
 * @property bool $important A state of importance of the additional field.
 * @property int $priority A priority of the additional field.
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
        'priority',
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
