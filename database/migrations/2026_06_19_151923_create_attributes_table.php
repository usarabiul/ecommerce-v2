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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('slug', 250)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('src_id')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('view')->default(0);
            $table->tinyInteger('menu_type')->default(0)
                ->comment('0=Custom Link, 1=Pages, 2=Post Categories, 3=Service Categories');
            $table->string('location', 100)->nullable();
            $table->tinyInteger('target')->default(0);
            $table->string('icon', 100)->nullable();
            $table->string('seo_title', 100)->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->tinyInteger('type')->default(0)
                ->comment('0=Category, 1=Slider, 2=Brand, 3=Client, 4=Galleries, 5=Portfolio, 6=Blog Category, 7=Blog Tags, 8=Menus, 13=Coupon, 14=Promotion');
            $table->string('status', 20)->nullable()->comment('temp, active, inactive');
            $table->tinyInteger('featured')->default(0);
            $table->integer('addedby_id')->nullable();
            $table->integer('editedby_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
