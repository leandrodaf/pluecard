<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTableModelCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('background');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE models_cards ADD FULLTEXT fulltext_index_name (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('models_cards', function (Blueprint $table) {
            $table->dropIndex('fulltext_index_name');
        });

        Schema::dropIfExists('models_cards');
    }
}
