<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToDarbaiNuotraukos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darbai_nuotraukos', function (Blueprint $table) {
            $table->string('video')->nullable()->change();
            $table->string('aprasymas_lt')->nullable()->change();
            $table->string('aprasymas_en')->nullable()->change();
            $table->string('aprasymas_ru')->nullable()->change();
            $table->string('img')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darbai_nuotraukos', function (Blueprint $table) {
            $table->string('video')->nullable(false)->change();
            $table->string('aprasymas_lt')->nullable(false)->change();
            $table->string('aprasymas_en')->nullable(false)->change();
            $table->string('aprasymas_ru')->nullable(false)->change();
            $table->string('img')->nullable(false)->change();
        });
    }
}
