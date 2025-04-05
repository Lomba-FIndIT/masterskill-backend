<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    /** @use HasFactory<\Database\Factories\SpeakerFactory> */
    use HasFactory;

    protected $fillable = [
        'speaker_name',
        'speaker_title'
    ];

    public function webinars(): BelongsToMany
    {
        return $this->belongsToMany(Webinar::class);
    }
}
