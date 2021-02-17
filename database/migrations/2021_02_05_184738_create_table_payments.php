<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('external_id');
            $table->boolean('binary_mode');
            $table->string('operation_type');
            $table->json('order')->nullable();
            $table->string('external_reference')->nullable();
            $table->string('status');
            $table->string('status_detail');
            $table->datetime('date_created');
            $table->datetime('last_modified')->nullable();
            $table->boolean('live_mode');
            $table->datetime('date_last_updated');
            $table->datetime('date_of_expiration')->nullable();
            $table->datetime('date_approved');
            $table->datetime('money_release_date');
            $table->string('currency_id');
            $table->float('transaction_amount');
            $table->float('transaction_amount_refunded')->nullable();
            $table->integer('collector_id');
            $table->string('payment_method_id');
            $table->string('payment_type_id');
            $table->json('transaction_details');
            $table->json('fee_details');
            $table->integer('differential_pricing_id')->nullable();
            $table->float('application_fee')->nullable();
            $table->boolean('capture')->nullable();
            $table->boolean('captured');
            $table->string('call_for_authorize_id')->nullable();
            $table->string('statement_descriptor');
            $table->json('refunds')->nullable();
            $table->json('additional_info');
            $table->integer('campaign_id')->nullable();
            $table->float('coupon_amount')->nullable();
            $table->integer('installments');
            $table->string('token');
            $table->string('description')->nullable();
            $table->string('notification_url')->nullable();
            $table->string('issuer_id');
            $table->json('metadata')->nullable();
            $table->string('callback_url')->nullable();
            $table->string('coupon_code')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
}
