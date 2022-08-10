<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_images', function (Blueprint $table) {
            $table->id();
            $table->integer('blog_id')->nullable();
            $table->string('image_url')->nullable();
            $table->tinyinteger('is_primary_image')->default(0)->comment('Yes 1,No 0')->nullable();
            $table->tinyinteger('is_active')->default(1)->comment('Yes 1,No 0')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_images');
    }
}
