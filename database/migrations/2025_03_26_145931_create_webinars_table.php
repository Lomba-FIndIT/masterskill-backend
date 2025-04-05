<?php

use App\Models\Schedule;
use App\Models\Speaker;
use App\Models\User;
use App\Models\Webinar;
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
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(Schedule::class);
            $table->string('webinar_link');
            $table->date('duration');
            $table->timestamps();
        });

        Schema::create('user_webinar', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Webinar::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webinars');
        Schema::dropIfExists('user_webinar');
    }
};
