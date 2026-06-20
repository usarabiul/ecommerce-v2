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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('src_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('name', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('title', 200)->nullable();
            $table->string('website', 100)->nullable();
            $table->text('description')->nullable();
            $table->integer('rating')->default(0)->comment('1,2,3,4,5,=rating');
            $table->string('type')->comment('Product, Vendor, Package');
            $table->string('status', 20)->nullable()->comment('temp, active, inactive');
            $table->tinyInteger('featured')->default(0);
            $table->integer('addedby_id')->nullable();
            $table->integer('editedby_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
