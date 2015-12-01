<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_files', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('request_id')->unsigned();
            $table->integer('file_id')->unsigned();

            $table->index('request_id');
            $table->index('file_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shared_files');
    }
}
