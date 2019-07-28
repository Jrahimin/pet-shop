<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeBaneriaiColumnNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baneriai', function (Blueprint $table) {
            $table->string('pavadinimas')->nullable()->change();
            $table->string('link')->nullable()->change();
            $table->string('kodas')->nullable()->change();
            $table->integer('data_nuo')->nullable()->change();
            $table->integer('data_iki')->nullable()->change();
            $table->string('clicktag')->nullable()->change();
            $table->integer('parodymai')->nullable()->change();
            $table->integer('paspaudimai')->nullable()->change();
            $table->integer('kriterijus')->nullable()->change();
            $table->integer('vieta')->nullable()->change();
            $table->integer('rodymo_tipas')->nullable()->change();
            $table->string('pages')->nullable()->change();
            $table->integer('paspausta')->nullable()->change();
            $table->integer('parodyta')->nullable()->change();
            $table->string('img')->nullable()->change();
            $table->string('lang')->nullable()->change();
            $table->integer('aktyvus')->nullable()->change();
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
            $table->string('pavadinimas')->nullable(false)->change();
            $table->string('link')->nullable(false)->change();
            $table->string('kodas')->nullable(false)->change();
            $table->integer('data_nuo')->nullable(false)->change();
            $table->integer('data_iki')->nullable(false)->change();
            $table->string('clicktag')->nullable(false)->change();
            $table->integer('parodymai')->nullable(false)->change();
            $table->integer('paspaudimai')->nullable(false)->change();
            $table->integer('kriterijus')->nullable(false)->change();
            $table->integer('vieta')->nullable(false)->change();
            $table->integer('rodymo_tipas')->nullable(false)->change();
            $table->string('pages')->nullable(false)->change();
            $table->integer('paspausta')->nullable(false)->change();
            $table->integer('parodyta')->nullable(false)->change();
            $table->string('img')->nullable(false)->change();
            $table->string('lang')->nullable(false)->change();
            $table->integer('aktyvus')->nullable(false)->change();

        });
    }
}
