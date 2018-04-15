<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TextDraft extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_drafts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('feature_id');
            $table->integer('document_id');
            $table->longText('text_input');
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
        Schema::dropIfExists('text_drafts');
    }
}
