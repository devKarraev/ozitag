<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlatPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flat_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('post_id');
            $table->string('price', 255);

            $table->text('main_content');
            $table->text('image_path')->nullable();
            $table->text('post_status');

            $table->timestamps();
            $table->index('price');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flat_posts');
    }
}
