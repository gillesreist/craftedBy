<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasUuids;
    use HasSlug;
    protected $guarded = [];

    protected $hidden = ['id', 'created_at', 'updated_at', 'customization_id', 'crafter_id', 'pivot'];

    public function crafter(): BelongsTo
    {
        return $this->belongsTo(Crafter::class, 'crafter_id');
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }

    public function customization(): BelongsTo
    {
        return $this->belongsTo(Customization::class, 'customization_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class);
    }

      /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

}
