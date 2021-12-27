<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class);
    }
}
