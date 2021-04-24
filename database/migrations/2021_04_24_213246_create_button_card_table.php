<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateButtonCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buttons_card', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('background');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('buttons_card');
    }
}
