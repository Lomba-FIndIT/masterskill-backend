<?php

use App\Models\Category;
use App\Models\Discussion;
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
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->foreignIdFor(Category::class);
            $table->timestamps();
        });

        Schema::create('discussion_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Discussion::class);
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions');
        Schema::dropIfExists('discussion_user');
    }
};
