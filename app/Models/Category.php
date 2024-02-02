<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];
    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

}
