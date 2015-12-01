<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $repository = new \App\Repositories\Users;
        $repository->create([
            'name' => 'App Admin',
            'email' => 'admin@filesharing.com.au',
            'password' => '077c8NG5wi',
            'password_confirmation' => '077c8NG5wi',
            'super_admin'   => 1,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $user = \App\Models\User::where('email', '=', 'admin@filesharing.com.au')->delete();
    }
}