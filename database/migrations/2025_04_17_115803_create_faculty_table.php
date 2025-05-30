<?php

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
        Schema::create('faculty', function (Blueprint $table) {
            $table->id("faculty_id");
            $table->string('u_id')->nullable()->default(null);
            $table->string('faculty_name');
            $table->string('faculty_initial')->default(null);
            $table->string('faculty_email')->unique();
            $table->string('faculty_phone')->nullable();
            $table->string('faculty_department')->nullable();
            $table->string('courses')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};
