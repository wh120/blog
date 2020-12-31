<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_tags', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags');

           $table->unsignedInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles');

            


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_tags');
    }
}
