<?php

namespace Hamedov\Taxonomies\Models;

use Illuminate\Database\Eloquent\Model;

class Taxable extends Model
{
    protected $fillable = [
    	'taxonomy_id', 'taxable_id', 'taxable_type',
    ];

    public function taxable()
    {
    	return $this->morphTo();
    }

    public function taxonomy()
    {
    	return $this->belongsTo(config('taxonomies.taxonomies_model', Taxonomy::class));
    }
}
