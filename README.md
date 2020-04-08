# Laravel taxonomies
Assign categories, tags, types, etc to your models or any taxonomy that can be used to classify models.

# Installation
```composer require hamedov/laravel-taxonomies```

# Publish config file
```php artisan vendor:publish --provider="Hamedov\Taxonomies\TaxonomyServiceProvider" --tag="config"```

# Create taxonomies
  ```
  use Hamedov\Taxonomies\Models\Taxonomy;
  
  // Create new category
  $category = Taxonomy::create([
    'title' => 'Electronics', // The only required field
    'description' => 'Electronics category description',
    'font_icon' => 'fa-laptop', // Enables you to assign font icons to your taxonomies
    'type' => 'category',
    'parent_id' => null, // Allow for sub categories/taxonomies
  ]);
  
  // Or create a new tag
  $tag = Taxonomy::create([
    'title' => 'Php', // The only required field
    'type' => 'tag',
  ]);
  
  // Create any custom taxonomy
  $ticket_class = Taxonomy::create([
    'title' => 'Class A',
    'description' => '',
    'font_icon' => 'fa-ticket',
    'type' => 'ticket_class',
  ]);
  ```
  # Assign image icons to taxonomies
- The package uses spatie/laravel-medialibrary to allow you to assign image icons to taxonomies.
- You can configure icons collection name and icon conversions in the config file, you can define as many conversions as you like.
- The icon collection is a single file collection.
  ```
  'icon_collection_name' => 'taxonomy_icons',
	'icon_conversions' => [
		'medium' => [120, 120], // Width, Height
    'small' => [90, 90],
	],
  
  ```
- Assign an image to taxonomy
  ```
  // You can pass any parameter that can be passed to addMedia method of medialibrary package
  $taxonomy->setIcon(request()->file('icon'));
  ```
- Refer to [Media library documentation](https://docs.spatie.be/laravel-medialibrary/v7/introduction) for more info.
# Translate taxonomies
- You can also set translations for the `title` and `description` columns using spatie/laravel-translatable package.
- You database server must support json columns for translations to work.
- Refer to [https://github.com/spatie/laravel-translatable](https://github.com/spatie/laravel-translatable)
  for more information on how to translate these fields.

# Manage model taxonomies
- To be able to assign taxonomies to any of your models, the model must use the `HasTaxonomies` trait.
  ```
  use Hamedov/Taxonomies/Traits/HasTaxonomies;

  class Post extends Model {
    use HasTaxonomies;
    
  }
  ```
- Assign taxonomies to models
  ```
  $post = Post::find(1);
  $taxonomy = Taxonomy::find(1);
  
  // Add taxonomy to post
  $post->addTaxonomy($taxonomy->id);
  
  // Or add many taxonomies at once
  $post->addTaxonomies([1, 2, 3]);
  
  // Set new taxonomies and remove existing
  // This will remove all post taxonomies of type category
  // And then adds the new specified taxonomies
  // To remove all old taxonomies of all types remove the second parameter
  $post->setTaxonomies([1, 2, 3], 'category');
  
  // Remove post taxonomy
  $post->removeTaxonomy($taxonomy_id = 1);
  
  // Remove post taxonomies of specific type
  $post->removeTaxonomies('tag', [5, 6, 7]);
  // Or remove all post tags
  $post->removeTaxonomies('tag');
  
  // Remove all post taxonomies
  $post->removeAllTaxonomies();
  
  // Get post taxonomies by type
  $categories = $post->taxonomies('category')->get();
  $tags = $post->taxonomies('tags')->get();
  
  // Or get all taxonomies at once
  $taxonomies = $post->taxonomies;
  ```

- Available scopes
  ```
  // Scope taxonomies with specific type
  Taxonomy::type('category')->get();
  
  // Scope entries with a specific taxonomy
  $taxonomy = Taxonomy::find(1);
  $posts = Post::hasTaxonomy($taxonomy->id)->get();
  
  // Scope entries which have any of the specified taxonomies
  $posts = Post::hasAnyTaxonomy([1, 2, 3])->get();

# Query models related to specific taxonomy
  ```
  // We can only get one type of taxable models at once
  // This is a limitation of many to many polymorphic relationships
  $taxonomy = Taxonomy::find(1);
  $taxonomy_posts = $taxonomy->taxables('App\Post')->get();
  $taxonomy_products = $taxonomy->taxables('App\Product')->get();
  
  // To get all models at once, we can do something like this
  $taxonomy->load('taxes', 'taxes.taxable');
  foreach($taxonomy->taxes as $tax)
  {
    // $tax->taxable here is the post or product or any thing else.
    dd($tax->taxable);
  }
  ```
  
# Query related entries in pivot table
- You can query entries related to specific taxonomy in pivot table
  ```
  $taxonomy = Taxonomy::find(1);
  $pivot_entries = $taxonomy->taxes()->where([
    // Filter by taxonomy_id, taxable_id, taxable_type
  ])->get();

- You can also do the same from other model perspective
  ```
  $post = Post::find(1);
  $pivot_entries = $post->taxes()->where([
    // Filter by taxonomy_id, taxable_id, taxable_type
  ])->get();
  ```

# License
Released under the Mit license, see [LICENSE](https://github.com/hamedov93/laravel-taxonomies/blob/master/LICENSE)
