<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            //author id
            $table->bigInteger('user_id')
                ->foreign('user_id')
                ->unsigned()
                ->references('id')
                ->on('users');
            //post id
            $table->bigInteger('post_id')
                ->foreign('post_id')
                ->unsigned()
                ->references('id')
                ->on('blog_posts')
                ->onDelete('cascade');
            //more details
            $table->boolean('status');
            $table->text('comment');
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
        Schema::dropIfExists('blog_comments');
    }
}
