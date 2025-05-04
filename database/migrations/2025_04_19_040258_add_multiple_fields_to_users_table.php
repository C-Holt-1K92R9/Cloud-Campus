<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('u_id')->after('id')->nullable()->default(null);
            $table->enum('type', ['admin', 'faculty', 'student'])->after('u_id')->nullable()->default(null);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('u_id');
            $table->dropColumn('type');
        });
    }
};
