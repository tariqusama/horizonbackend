<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dynamic_form_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamps();

            $table->foreign('dynamic_form_id')->references('id')->on('dynamic_forms')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->unique(['dynamic_form_id', 'service_id']);
        });

        // Migrate existing data
        $existingForms = DB::table('dynamic_forms')->whereNotNull('service_id')->get();
        foreach ($existingForms as $form) {
            DB::table('dynamic_form_service')->insert([
                'dynamic_form_id' => $form->id,
                'service_id' => $form->service_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the service_id column
        Schema::table('dynamic_forms', function (Blueprint $table) {
            if (Schema::hasColumn('dynamic_forms', 'service_id')) {
                // Ignore drop foreign error if it doesn't exist
                try {
                    $table->dropForeign(['service_id']);
                } catch (\Exception $e) {}
                
                $table->dropColumn('service_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable();
        });

        // Restore service_id
        $links = DB::table('dynamic_form_service')->get();
        foreach ($links as $link) {
            DB::table('dynamic_forms')
                ->where('id', $link->dynamic_form_id)
                ->update(['service_id' => $link->service_id]);
        }

        Schema::dropIfExists('dynamic_form_service');
    }
};
