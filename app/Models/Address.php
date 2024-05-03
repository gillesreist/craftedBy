<?php

namespace App\Models;

use App\Enums\AddressEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;
    use HasUuids;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function getTypeAttribute(): string
    {
        return AddressEnum::from($this->attributes['type'])->name;
    }
 
    public function setTypeAttribute($typeName): void
    {
        $this->attributes['type'] = constant(\App\Enums\AddressEnum::class . '::' . $typeName);
    }


}
