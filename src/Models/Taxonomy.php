<?php

namespace Hamedov\Taxonomies\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Taxonomy extends Model implements HasMedia
{
	use HasTranslations, HasMediaTrait, HasSlug, HasRecursiveRelationships;

    protected $table = 'taxonomies';

    protected $fillable = [
    	'title', 'description', 'font_icon', 'type', 'parent_id',
    ];

    public $translatable = [
    	'title', 'description',
    ];

    public function registerMediaCollections()
	{
	    $this
	    	->addMediaCollection(config('taxonomies.icon_collection_name', 'taxonomy_icons'))
            ->singleFile()
	    	->registerMediaConversions(function ($media) {
	    		$conversions = config('taxonomies.icon_conversions', ['thumb' => [120, 120]]);
	    		foreach ($conversions as $key => $value)
	    		{
	    			$this
		                ->addMediaConversion($key)
		                ->width($value[0])
		                ->height($value[1]);
	    		}
        	});
	}

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        $options = SlugOptions::create();
        $options->generateSlugsFrom('title');
        $options->saveSlugsTo('slug');
        $options->usingSeparator(config('taxonomies.slug_separator', '-'));
        $options->usingLanguage('en');

        if (config('taxonomies.unique_slugs', true) === false)
        {
            $options->allowDuplicateSlugs();
        }

        return $options;
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

    public function parent()
    {
        return $this->belongsTo(config('taxonomies.taxonomies_model', get_class($this)), 'parent_id');
    }

    public function icon()
    {
        return $this->morphOne(config('medialibrary.media_model'), 'model')
            ->where('collection_name', config('taxonomies.icon_collection_name'));
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
