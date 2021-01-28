<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmationAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirmationAccounts', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 255)->index();
            $table->dateTime('validatedAt');
            $table->timestamps();
            $table->unsignedInteger('userId')->unique()->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('confirmationAccounts');
    }
}
