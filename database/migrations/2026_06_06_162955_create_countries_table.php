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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250)->nullable();
            $table->string('bn_name', 100)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->integer('type')->nullable()->comment('1=country, 2=division, 3=district, 4=thana');
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
