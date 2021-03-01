<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nps', function (Blueprint $table) {
            $table->id();
            $table->string('relation');
            $table->bigInteger('relation_id')->nullable();
            $table->enum('rating', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['rating', 'created_at']);
            $table->index(['relation', 'relation_id', 'rating', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nps');
    }
}
