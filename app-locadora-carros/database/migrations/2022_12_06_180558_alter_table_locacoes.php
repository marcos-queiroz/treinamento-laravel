<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLocacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locacoes', function (Blueprint $table) {
            $table->dateTime('data_final_realizado_periodo')->nullable()->change();
            $table->integer('km_final')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locacoes', function (Blueprint $table) {
            $table->dateTime('data_final_realizado_periodo')->change();
            $table->integer('km_final')->change();
        });
    }
}
