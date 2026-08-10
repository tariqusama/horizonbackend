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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique(); // e.g., TKT-1042
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('Open');
            $table->string('priority')->default('Medium');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Client
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Staff/Manager
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
