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
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['price', 'tier']);
            $table->renameColumn('name', 'title');
            $table->renameColumn('description', 'subtitle');
            
            $table->foreignId('service_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('starting_price')->nullable();
            $table->string('processing_time')->nullable();
            $table->json('requirements')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->integer('order_index')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->dropColumn([
                'service_category_id', 
                'starting_price', 
                'processing_time', 
                'requirements', 
                'is_popular', 
                'order_index'
            ]);
            $table->renameColumn('title', 'name');
            $table->renameColumn('subtitle', 'description');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('tier')->default('Standard');
        });
    }
};
