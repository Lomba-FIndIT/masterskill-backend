<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    protected $fillable = [
        'per_month'
    ];

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
