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
        Schema::create('dynamic_form_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_section_id')->constrained()->onDelete('cascade');
            $table->string('question_text');
            $table->text('help_text')->nullable();
            $table->string('field_type'); // text, radio, checkbox, date, select, number, etc.
            $table->string('field_name'); // e.g. firstName, aNumber
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->json('validation_rules')->nullable(); // e.g. min, max length
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_form_questions');
    }
};
