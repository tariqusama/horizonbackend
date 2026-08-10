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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('status')->default('Active'); // Active, Suspended
            $table->string('initials')->nullable();
            $table->string('color')->nullable();
            $table->string('role')->default('Client')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'country', 'status', 'initials', 'color']);
            $table->enum('role', ['admin', 'manager', 'user'])->default('user')->change();
        });
    }
};
