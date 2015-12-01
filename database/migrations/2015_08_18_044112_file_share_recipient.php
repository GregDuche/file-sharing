<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileShareRecipient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_file_recipients', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('request_id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->string('email');
            $table->index('request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shared_file_recipients');
    }
}
