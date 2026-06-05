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
        Schema::create('generals', function (Blueprint $table) {
            $table->id();
            $table->string('title',200)->nullable();
            $table->string('subtitle',200)->nullable();
            $table->string('website',200)->nullable();
            $table->string('logo',100)->nullable();
            $table->string('favicon',100)->nullable();
            $table->string('mobile',200)->nullable();
            $table->string('mobile_two',200)->nullable();
            $table->string('email',200)->nullable();
            $table->string('email_two',200)->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->text('postal_address')->nullable();
            
            // SEO Meta Tags
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_author',200)->nullable();
            $table->string('meta_title',200)->nullable();
            
            // Custom Scripts
            $table->text('script_head')->nullable();
            $table->text('script_body')->nullable();
            $table->text('copyright_text')->nullable();
            
            // Mail Configuration
            $table->string('mail_driver',100)->nullable();
            $table->string('mail_host',100)->nullable();
            $table->string('mail_port',10)->nullable();
            $table->string('mail_username',100)->nullable();
            $table->string('mail_password',100)->nullable();
            $table->string('mail_encryption',20)->nullable();
            $table->string('mail_from_name',100)->nullable();
            $table->string('mail_from_address',100)->nullable();
            $table->boolean('mail_status')->default(0); // 0 = Inactive, 1 = Active
            
            // SMS Configuration
            $table->string('sms_username',100)->nullable();
            $table->string('sms_password',100)->nullable();
            $table->string('sms_senderid',100)->nullable();
            $table->text('sms_url_masking')->nullable();
            $table->text('sms_url_nonmasking')->nullable();
            $table->string('sms_type',100)->nullable();
            $table->boolean('sms_status')->default(0);
            
            // Social Login (Facebook, Twitter, Google)
            $table->string('fb_app_id',100)->nullable();
            $table->string('fb_app_secret',100)->nullable();
            $table->text('fb_app_redirect_url',200)->nullable();
            $table->string('tw_app_id',100)->nullable();
            $table->string('tw_app_secret',100)->nullable();
            $table->text('tw_app_redirect_url',200)->nullable();
            $table->string('google_client_id',100)->nullable();
            $table->string('google_client_secret',100)->nullable();
            $table->text('google_client_redirect_url',200)->nullable();
            
            // Social Media Links
            $table->string('facebook_link',200)->nullable();
            $table->string('twitter_link',200)->nullable();
            $table->string('instagram_link',200)->nullable();
            $table->string('linkedin_link',200)->nullable();
            $table->string('pinterest_link',200)->nullable();
            $table->string('youtube_link',200)->nullable();
            $table->string('whatsapp_link',200)->nullable();
            $table->string('messanger_link',200)->nullable();
            
            // Localization & Settings
            $table->string('currency',5)->default('BDT');
            $table->integer('currency_decimal')->default(2);
            $table->string('currency_position',10)->default('left');
            $table->string('theme',100)->default('frontend');
            $table->string('adminTheme',100)->default('admin');
            $table->decimal('balance', 16, 2)->default(0.00);
            
            $table->timestamps();
        });

        // Insert default settings
        DB::table('generals')->insert([
            'id' => 1,
            'title' => 'My Website',
            'subtitle' => 'E-commerce Platform',
            'website' => 'https://example.com',
            'balance' => 0.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generals');
    }
};