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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->json('permission')->nullable();
            $table->string('status', 20)->nullable()->default('active')->comment('active, inactive');
            $table->integer('addedby_id')->nullable();
            $table->integer('editedby_id')->nullable();
            $table->timestamps();
        });

        // Insert default permissions
        DB::table('permissions')->insert([
            'name' => 'Super Admin',
            'permission' => null,
            'status' => 'active',
            'addedby_id' => 1
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
