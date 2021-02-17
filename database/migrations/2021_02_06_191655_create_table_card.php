<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            $table->string('external_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->integer('expiration_month');
            $table->integer('expiration_year');
            $table->string('first_six_digits');
            $table->string('last_four_digits');
            $table->json('payment_method')->nullable();
            $table->json('security_code')->nullable();
            $table->json('issuer')->nullable();
            $table->json('cardholder');
            $table->datetime('date_created');
            $table->datetime('date_last_updated');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');

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
        Schema::dropIfExists('cards');
    }
}
