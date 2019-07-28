<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_log', function (Blueprint $table) {
            $table->integer('package_id')->nullable();
            $table->integer('stock_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_log', function (Blueprint $table) {
            $table->dropColumn('package_id') ;
            $table->dropColumn('stock_id') ;
        });
    }
}
