<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToGaminiuKategorijos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gaminiu_kategorijos', function (Blueprint $table) {
            $table->string('pavadinimas_en')->nullable()->change();
            $table->string('imgpavadinimas')->nullable()->change();
            $table->string('raktazodziai')->nullable()->change();
            $table->string('paryskinta')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gaminiu_kategorijos', function (Blueprint $table) {
            $table->string('pavadinimas_en')->nullable(false)->change();
            $table->string('imgpavadinimas')->nullable(false)->change();
            $table->string('raktazodziai')->nullable(false)->change();
            $table->string('paryskinta')->default(NULL)->change();
        });
    }
}
