<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('code');
            $table->integer('datefrom');
            $table->integer('datetill');
            $table->double('amount_percent')->nullable();
            $table->double('amount_fixed')->nullable();
            $table->boolean('all_product');
            $table->boolean('category_check');
            $table->boolean('manufacturer_check');
            $table->boolean('show_promotion_percent');
            $table->boolean('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
