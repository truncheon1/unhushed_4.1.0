<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            //post id
            $table->id();
            //author id
            $table->bigInteger('user_id')
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            //slug
            $table->string('slug')
                ->unique();
            //text columns
            $table->string('title')
                ->unique();
            $table->text('description');
            $table->text('post');
            //more details
            $table->string('status');
            $table->string('comments');
            $table->string('tags');
            //time created-updated
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
        Schema::dropIfExists('blog_posts');
    }
}
