<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];
    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];


    public function skus(): BelongsToMany
    {
        return $this->belongsToMany(Sku::class)->withPivot('attribute_value');
    }
}
