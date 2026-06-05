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
        Schema::create('orders', function (Blueprint $table) {

            $table->id(); 
            $table->string('invoice', 100)->nullable();
            $table->integer('user_id')->nullable();

            // Billing Contact Information
            $table->string('name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 50)->nullable();

            // Billing Address Details
            $table->integer('division')->nullable();
            $table->integer('district')->nullable();
            $table->integer('city')->nullable();
            $table->string('city_name', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('postal_code')->nullable();

            // Shipping Address Flag
            $table->tinyInteger('shipping_address_status')->default(0);

            // Shipping Contact Information
            $table->string('shipping_name', 100)->nullable();
            $table->string('shipping_last_name', 100)->nullable();
            $table->string('shipping_company', 100)->nullable();
            $table->string('shipping_mobile', 20)->nullable();
            $table->string('shipping_email', 100)->nullable();

            // Shipping Address Details
            $table->integer('shipping_division')->nullable();
            $table->integer('shipping_district')->nullable();
            $table->integer('shipping_city')->nullable();
            $table->string('shipping_city_name', 100)->nullable();
            $table->string('shipping_postal_code', 100)->nullable();
            $table->text('shipping_address')->nullable();

            // Core Statuses & Returns
            $table->string('order_status', 20)->default('pending')->comment('temp, pending, confirmed, on delivery, completed, cancelled, return');
            $table->string('return_status', 20)->default('pending')->comment('pending, confirmed, on delivery, completed, cancelled');
            $table->float('return_amount', 10, 2)->default(0.00);

            //Payment & Order Meta
            $table->string('payment_status', 10)->default('unpaid')->comment('unpaid, partial, paid');
            $table->string('payment_method', 50)->nullable();
            $table->string('transection', 100)->nullable()->comment('Transaction Hash / ID');
            $table->tinyInteger('emi_status')->default(0);
            $table->string('order_type', 15)->default('customer_order')->comment('customer_order, pos_order, purchase_order, quotation_order');

            // Quantities and Items
            $table->float('total_price', 10, 2)->default(0.00);
            $table->integer('total_items')->default(0);
            $table->integer('total_qty')->default(0)->nullable();

            // Financial Breakdown Calculations
            $table->float('shipping_charge', 10, 2)->default(0.00);
            $table->float('tax', 10, 2)->default(0.00);
            $table->string('discount_type', 20)->nullable()->comment('Percentage, Flat');
            $table->float('discount', 10, 2)->default(0.00);
            $table->float('discount_price', 10, 2)->default(0.00);
            $table->float('coupon_discount', 10, 2)->default(0.00);
            $table->float('grand_total', 10, 2)->default(0.00);
            $table->float('paid_amount', 10, 2)->default(0.00);
            $table->float('due_amount', 10, 2)->default(0.00);
            $table->float('extra_amount', 10, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->integer('coupon_id')->nullable();

            $table->integer('order_delivery_By')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
