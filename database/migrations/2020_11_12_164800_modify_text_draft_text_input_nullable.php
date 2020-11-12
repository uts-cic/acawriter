<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTextDraftTextInputNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('text_drafts', function (Blueprint $table) {
            //
            $table->longText('text_input')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('text_drafts', function (Blueprint $table) {
            $table->longText('text_input')->nullable(false)->change();
        });
    }
}
