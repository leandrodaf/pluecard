<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResetPasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resetPasswords', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 255)->index();
            $table->dateTime('validatedAt');
            $table->boolean('enable')->default(true);
            $table->timestamps();
            $table->unsignedInteger('userId')->foreign('userId')->references('id')->on('users');
            $table->index(['userId', 'enable', 'hash', 'validatedAt']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resetPasswords');
    }
}
