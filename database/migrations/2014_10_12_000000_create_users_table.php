<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('tempat_lahir', 25);
            $table->date('tgl_lahir');
            $table->text('address');
            $table->string('email')->unique();
            $table->string('username', 50);
            $table->string('password');
            $table->string('img', 50);
            $table->rememberToken();
            $table->dateTime('login');
            $table->dateTime('logout');
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
        Schema::dropIfExists('users');
    }
}
