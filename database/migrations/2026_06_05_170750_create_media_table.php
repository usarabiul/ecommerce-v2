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
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Polymorphic Source Reference
            $table->integer('src_id')->nullable();
            $table->tinyInteger('src_type')->default(0)
                  ->comment('0=media, 1=post, 2=category, 3=attribute, 4=Menus, 5=review, 6=Users, 7=General');

            // File Usage and Format Types
            $table->tinyInteger('use_of_file')->default(0)
                  ->comment('0=media, 1=image, 2=banner, 3=gallery, 4=icon');
            $table->tinyInteger('file_type')->default(0)
                  ->comment('0=unknown, 1=image, 2=pdf, 3=doc, 4=Zip/rar, 5=Video, 6=audio');

            // File Identifiers & Metadata
            $table->string('file_name', 200)->nullable();
            $table->string('file_rename', 200)->nullable();
            $table->string('alt_text', 200)->nullable();
            $table->string('caption', 200)->nullable();
            $table->text('description')->nullable();

            // File URLs (Responsive Variants)
            $table->string('file_url', 100)->nullable();
            $table->string('file_url_sm', 100)->nullable();
            $table->string('file_url_md', 100)->nullable();
            $table->string('file_url_lg', 100)->nullable();

            // Physical File Specifications
            $table->string('file_size', 100)->nullable();
            $table->string('file_path', 100)->nullable();
            $table->string('mine_type', 100)->nullable();
            $table->integer('drag')->default(0);

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
        Schema::dropIfExists('media');
    }
};
