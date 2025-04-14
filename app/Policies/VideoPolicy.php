<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    public function stream (User $user, Video $video) {
        $course = Course::find($video->course_id);

        $student = $course->students()->wherePivot('user_id', $user->id)->first();

        $payment = Payment::find($student->pivot->payment_id);

        return $video->free || $payment->paid;
    }
}
