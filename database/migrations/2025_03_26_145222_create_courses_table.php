<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name');
            $table->foreignIdFor(Category::class);
            $table->bigInteger('price');
            $table->foreignIdFor(User::class, 'instructor_id');
            $table->string('img_url')->nullable();
            $table->bigInteger('total_duration')->default(0);
            $table->double('ratings')->default(0);
            $table->timestamps();
        });
        
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Payment::class);
            $table->double('rating')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_user');
    }
};
