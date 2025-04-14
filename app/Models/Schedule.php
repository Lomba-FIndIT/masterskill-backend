<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'start',
        'end'
    ];

    public function webinars(): HasMany
    {
        return $this->hasMany(Webinar::class);
    }
}
