<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPakutoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pakuotes', function (Blueprint $table) {
            $table->integer('color_id')->nullable();
            $table->integer('size_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pakuotes', function (Blueprint $table) {
            $table->dropColumn('color_id');
            $table->dropColumn('size_id');
        });
    }
}
