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
        Schema::table('signup_goals', function (Blueprint $table) {
            $table->foreignId('default_service_id')->nullable()->constrained('services')->nullOnDelete();
        });

        Schema::table('signup_questions', function (Blueprint $table) {
            $table->json('service_mappings')->nullable()->after('skip_to_end_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signup_goals', function (Blueprint $table) {
            $table->dropForeign(['default_service_id']);
            $table->dropColumn('default_service_id');
        });

        Schema::table('signup_questions', function (Blueprint $table) {
            $table->dropColumn('service_mappings');
        });
    }
};
