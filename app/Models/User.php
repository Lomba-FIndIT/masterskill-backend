<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'address',
        'phone_number',
        'img_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function applied_courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withPivot(['payment_id', 'rating']);
    }

    public function created_courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class);
    }

    public function discussions(): BelongsToMany
    {
        return $this->belongsToMany(Discussion::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function webinars(): BelongsToMany
    {
        return $this->belongsToMany(Webinar::class);
    }

    public function student_review_cv(): HasMany
    {
        return $this->hasMany(ReviewCv::class, 'student_id');
    }

    public function hrd_review_cv(): HasMany
    {
        return $this->hasMany(ReviewCv::class, 'hrd_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }
}
