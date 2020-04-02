<?php

namespace Hamedov\Taxonomies\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class Taxonomy extends Model implements HasMedia
{
	use HasTranslations, HasMediaTrait;

    protected $fillable = [
    	'title', 'description', 'font_icon', 'type', 'parent_id'
    ];

    public $translatable = [
    	'title', 'description',
    ];

    public function registerMediaCollections()
	{
	    $this
	    	->addMediaCollection(config('taxonomies.icon_collection_name', 'taxonomy_icons'))
	    	->registerMediaConversions(function (Media $media) {
	    		$conversions = config('taxonomies.icon_conversions', ['thumb' => [120, 120]]);
	    		foreach ($conversions as $key => $value)
	    		{
	    			$this
		                ->addMediaConversion($key)
		                ->width($value[0])
		                ->height($value[1]);
	    		}
        	})->singleFile();
	}

    /**
     * Get models related to this taxonomy.
     */
    public function taxables($class_name)
    {
    	return $this->morphedByMany($class_name, 'taxable');
    }

    /**
     * Get all related entries in pivot table
     */
    public function taxes()
    {
    	return $this->hasMany(Taxable::class);
    }

    /**
     * Get taxonomies with specific type
     */
    public function scopeType($query, $type)
    {
    	return $query->where('taxonomies.type', $type);
    }

    public function setIcon($icon)
    {
        $this->addMedia($icon)->toMediaCollection(config('taxonomies.icon_collection_name', 'taxonomy_icons'));
    }
}
