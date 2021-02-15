<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePaymentTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('currency_id', 3);
            $table->float('amount');
            $table->integer('quantity');
            $table->integer('installments');

            $table->string('status')->nullable();

            $table->unsignedInteger('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('item_id')->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->unsignedInteger('payment_id')->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedInteger('payer_id')->foreign('payer_id')->references('id')->on('payers')->onDelete('cascade');
            $table->unsignedInteger('card_id')->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');

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
        Schema::dropIfExists('transactions');
    }
}
