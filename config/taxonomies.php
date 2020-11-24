<?php

return [
	// Taxonomies model to be used
	'taxonomies_model' => Hamedov\Taxonomies\Taxonomy::class,

	// Taxonomy icon collection name for media library
	'icon_collection_name' => 'taxonomy_icons',

	// Define conversions for taxonomy icons
	'icon_conversions' => [
		'thumb' => [120, 120], // Width, Height 
	],

	// Specify whether slugs should be unique
	'unique_slugs' => true,

	// Translation to use for slugs
	'slug_locale' => 'en',

	//Slug separator
	'slug_separator' => '-',
];
