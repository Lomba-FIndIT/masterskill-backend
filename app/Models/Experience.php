<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    protected $fillable = [
        'experience'
    ];

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
