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
        Schema::create('post_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('src_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('reff_id')->nullable();
            $table->tinyInteger('type')->default(0)
                ->comment('0=Category Post, 1=blog Category Post, 2=Blog Tags post');
            $table->string('status', 20)->nullable()->comment('temp, active, inactive');
            $table->integer('drag')->default(0);
            $table->integer('addedby_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_attributes');
    }
};
