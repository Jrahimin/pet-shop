<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCatColumnNullableInDarbai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darbai', function (Blueprint $table) {
            $table->integer('cat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darbai', function (Blueprint $table) {
            $table->integer('cat')->nullable(false)->change ;
        });
    }
}
