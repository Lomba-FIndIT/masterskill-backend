<?php

use App\Models\Cv;
use App\Models\Payment;
use App\Models\Schedule;
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
        Schema::create('review_cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'student_id');
            $table->foreignIdFor(User::class, 'hrd_id');
            $table->string('link_meeting');
            $table->foreignIdFor(Cv::class);
            $table->foreignIdFor(Schedule::class);
            $table->foreignIdFor(Payment::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_cvs');
    }
};
