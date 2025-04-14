<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    
    public function rate(User $user, Course $course): bool
    {
        $allowed = false;

        foreach ($course->students as $student) {
            if ($student->id === $user->id) {
                $allowed = true;
            }
        }
        return $allowed;
    }
}
