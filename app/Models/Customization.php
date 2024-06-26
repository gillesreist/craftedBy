<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customization extends Model
{
    use HasFactory;
    use HasUuids;
    protected $guarded = [];

    protected $hidden = ['id', 'created_at', 'updated_at'];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
