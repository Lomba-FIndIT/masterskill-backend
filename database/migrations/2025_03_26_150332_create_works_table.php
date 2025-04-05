<?php

use App\Models\Category;
use App\Models\Experience;
use App\Models\Salary;
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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('company_address');
            $table->string('job_description');
            $table->foreignIdFor(Experience::class);
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Salary::class);
            $table->string('hrd_email');
            $table->string('contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
