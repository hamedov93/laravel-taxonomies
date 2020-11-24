<?php

namespace Hamedov\Taxonomies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relatons\BelongsTo;
use Illuminate\Database\Eloquent\Relatons\MorphTo;

class Taxable extends Model
{
    /**
     * Attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
    	'taxonomy_id', 'taxable_id', 'taxable_type',
    ];

    /**
     * Taxable model relationship
     * @return \Illuminate\Database\Eloquent\Relatons\MorphTo
     */
    public function taxable(): MorphTo
    {
    	return $this->morphTo();
    }

    /**
     * Get related taxonomy
     * @return \Illuminate\Database\Eloquent\Relatons\BelongsTo
     */
    public function taxonomy()
    {
    	return $this->belongsTo(config('taxonomies.taxonomies_model', Taxonomy::class));
    }
}
