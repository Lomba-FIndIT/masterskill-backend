<?php

use App\Models\Speaker;
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
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->string('speaker_name');
            $table->string('speaker_title');
            $table->timestamps();
        });

        Schema::create('speaker_webinar', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Speaker::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Webinar::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
        Schema::dropIfExists('speaker_webinar');
    }
};
