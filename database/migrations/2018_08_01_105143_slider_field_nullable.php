<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SliderFieldNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->string('img2')->nullable()->change();
            $table->string('prod1')->nullable()->change();
            $table->string('prod2')->nullable()->change();
            $table->string('prod3')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->string('img2')->nullable(false)->change();
            $table->string('prod1')->nullable(false)->change();
            $table->string('prod2')->nullable(false)->change();
            $table->string('prod3')->nullable(false)->change();
        });
    }
}
