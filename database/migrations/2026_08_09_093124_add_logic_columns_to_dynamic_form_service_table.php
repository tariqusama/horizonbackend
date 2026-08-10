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
        Schema::table('dynamic_form_service', function (Blueprint $table) {
            $table->boolean('is_required')->default(true);
            $table->string('condition_code')->nullable()->comment('Code evaluating if the optional form should be generated, e.g., wants_ead, wants_ap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_form_service', function (Blueprint $table) {
            $table->dropColumn('is_required');
            $table->dropColumn('condition_code');
        });
    }
};
