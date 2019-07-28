<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultToBanneriaiColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baneriai', function (Blueprint $table) {
            $table->integer('parodymai')->default(0)->change();
            $table->integer('paspaudimai')->default(0)->change();
            $table->integer('rodymo_tipas')->default(0)->change();
            $table->integer('paspausta')->default(0)->change();
            $table->integer('parodyta')->default(0)->change();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baneriai', function (Blueprint $table) {
            $table->integer('parodymai')->default(null)->change();
            $table->integer('paspaudimai')->default(null)->change();
            $table->integer('rodymo_tipas')->default(null)->change();
            $table->integer('paspausta')->default(null)->change();
            $table->integer('parodyta')->default(null)->change();
        });
    }
}
