<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->string('name',100)->nullable();
            $table->string('email',100)->unique();
            $table->string('mobile',100)->nullable();
            $table->string('profile',200)->nullable();
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->text('postal_address')->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('city',100)->nullable();
            $table->string('district',100)->nullable();
            $table->string('division',100)->nullable();
            $table->string('country',100)->default('Bangladesh');
            $table->date('date_of_birth')->nullable();
            $table->string('gender',50)->nullable();
            $table->string('marital_status',50)->nullable();
            
            // Statuses & Flags
            $table->string('login_status',50)->default('offline');
            $table->string('status',50)->default('active'); // active, inactive, banned
            $table->boolean('featured')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            
            // Password & Tokens
            $table->string('password',100);
            $table->string('password_show',100)->nullable();
            $table->rememberToken();
            $table->string('api_token', 80)->unique()->nullable();
            $table->text('device_key')->nullable();
            $table->string('verify_code',100)->nullable();
            $table->boolean('verify_code_status')->default(0);
            
            // Financial & Roles
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->boolean('subscriber')->default(0);
            $table->boolean('customer')->default(1);
            $table->boolean('business')->default(0);
            $table->boolean('admin')->default(0);
            
            // Track System
            $table->unsignedBigInteger('addedby_id')->nullable();
            $table->timestamp('addedby_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email',100)->primary();
            $table->string('token',100)->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // default admin user creation
        DB::table('users')->insert([
            'id' => 1,
            'permission_id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'password_show' => '123456789',
            'admin' => 1,
            'customer' => 0,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};