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
        Schema::table('course', function (Blueprint $table) {
            $table->string('u_id')->after('course_id')->nullable();
            $table->string('status')->after('u_id')->default('Online');
            $table->string('class_work')->after('course_link')->nullable();
            $table->datetime('work_due_date')->after('class_work')->nullable();
            $table->string('class_work_link')->after('work_due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course', function (Blueprint $table) {
            //
        });
    }
};
