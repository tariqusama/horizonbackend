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
        Schema::create('signup_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signup_goal_id')->constrained('signup_goals')->cascadeOnDelete();
            $table->string('question_text');
            $table->json('options');
            $table->json('disqualifying_options')->nullable();
            $table->json('skip_to_end_options')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signup_questions');
    }
};
