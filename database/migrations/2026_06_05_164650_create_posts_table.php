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
        Schema::create('posts', function (Blueprint $table) {

            $table->id(); 
            $table->string('name', 200)->nullable();
            $table->string('slug', 250)->nullable();
            $table->tinyInteger('auto_slug')->default(0);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            
            // Inventory & Identifiers
            $table->integer('brand_id')->nullable();
            $table->string('sku_code', 100)->nullable();
            $table->string('bar_code', 20)->nullable();
            $table->integer('stock_out_limit')->default(0);
            $table->tinyInteger('stock_status')->default(0);
            $table->integer('quantity')->default(0);
            
            // Pricing & Discounts
            $table->float('purchase_price', 10, 2)->default(0.00);
            $table->float('final_price', 10, 2)->default(0.00);
            $table->float('discount', 10, 2)->default(0.00);
            $table->string('discount_type', 20)->default('percentage')->comment('percentage, flat');
            $table->float('regular_price', 10, 2)->default(0.00);
            
            // Offer Dates
            $table->timestamp('offer_start_date')->nullable();
            $table->timestamp('offer_end_date')->nullable();
            
            // 20-21. Order Limits
            $table->integer('min_order_quantity')->default(1);
            $table->integer('max_order_quantity')->nullable();
            
            // Shipping / Physical Specs
            $table->string('weight_unit', 100)->nullable();
            $table->string('weight_amount', 100)->nullable();
            $table->string('dimensions_unit', 100)->nullable();
            $table->string('dimensions_length', 100)->nullable();
            $table->string('dimensions_width', 100)->nullable();
            $table->string('dimensions_height', 100)->nullable();
            
            // Status Flags
            $table->tinyInteger('variation_status')->default(0);
            $table->tinyInteger('pos_status')->default(0);
            $table->tinyInteger('emi_status')->default(0);
            $table->tinyInteger('digital_status')->default(0);
            $table->tinyInteger('classified_status')->default(0);
            
            // SEO & Tags
            $table->string('seo_title', 100)->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->text('tags')->nullable();

            $table->bigInteger('view')->default(0);
            $table->string('template', 100)->nullable();
            $table->tinyInteger('type')->default(0)->comment('0=Page, 1=Post, 2=product');
            $table->string('status', 20)->default('temp')->comment('temp, active, inactive');
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
        Schema::dropIfExists('posts');
    }
};
