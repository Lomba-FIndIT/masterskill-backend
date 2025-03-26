<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'category_name'
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }
}
