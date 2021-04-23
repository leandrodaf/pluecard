<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePivotModelAndStyleButtons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_card_style_buttons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('models_cards')->onDelete('cascade');
            $table->unsignedBigInteger('style_buttons_id');
            $table->foreign('style_buttons_id')->references('id')->on('style_buttons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_card_style_buttons');
    }
}
