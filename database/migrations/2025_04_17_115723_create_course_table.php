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
        Schema::create('course', function (Blueprint $table) {
            $table->id("course_id");
            $table->string('course_code');
            $table->string('course_name');
            $table->text('course_description')->nullable();
            $table->string('course_section');
            $table->string("course_time");
            $table->string('course_days');
            $table->string('course_instructor');
            $table->text('course_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course');
    }
};
