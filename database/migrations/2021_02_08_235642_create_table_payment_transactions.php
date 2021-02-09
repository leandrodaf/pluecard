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
        Schema::create('payments_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('currency_id', 3);
            $table->float('amount');
            $table->integer('quantity');
            $table->integer('installments');

            $table->string('status')->nullable();

            $table->unsignedInteger('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('payments_item_id')->foreign('payments_item_id')->references('id')->on('payments_items')->onDelete('cascade');
            $table->unsignedInteger('payment_id')->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedInteger('payments_payer_id')->foreign('payments_payer_id')->references('id')->on('payments_payers')->onDelete('cascade');
            $table->unsignedInteger('payments_card_id')->foreign('payments_card_id')->references('id')->on('payments_cards')->onDelete('cascade');

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
        Schema::dropIfExists('payments_transactions');
    }
}
