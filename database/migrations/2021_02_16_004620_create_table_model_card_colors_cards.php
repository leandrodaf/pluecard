<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableModelCardColorsCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_card_colors_cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('models_cards')->onDelete('cascade');
            $table->unsignedBigInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colors_cards')->onDelete('cascade');

            $table->enum('status', ['PRIMARY', 'SECONDARY', 'CUSTOM']);

            // $table->unique(['model_id', 'color_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_card_colors_cards');
    }
}
