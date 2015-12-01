<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('files', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('path');
            $table->integer('size')->unsigned();
            $table->boolean('local')->default(1);
            $table->string('status');
            $table->text('attributes');
            $table->text('original_filename');
            $table->text('filename');
            $table->text('type')->nullable();
            $table->morphs('resolvable');
            $table->text('links')->nullable();

            $table->index('user_id');
            $table->index('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('files');
    }
}
