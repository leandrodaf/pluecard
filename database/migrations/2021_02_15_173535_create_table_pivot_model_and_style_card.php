<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePivotModelAndStyleCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_card_styles_cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('models_cards')->onDelete('cascade');
            $table->unsignedBigInteger('style_id');
            $table->foreign('style_id')->references('id')->on('styles_cards')->onDelete('cascade');

            $table->unique(['model_id', 'style_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_card_styles_cards');
    }
}
