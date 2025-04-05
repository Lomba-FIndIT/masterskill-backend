<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewCv extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewCvFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hrd_id',
        'link_meeting',
        'cv_id',
        'schedule_id',
        'payment_id'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function hrd(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hrd_id');
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
