<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeOrderItemsCxolumnsNullabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('order_id')->nullable()->change();
            $table->integer('item_id')->nullable()->change();
            $table->integer('package')->nullable()->change();
            $table->integer('color')->nullable()->change();
            $table->decimal('price')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->decimal('sum')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('order_id')->nullable(false)->change();
            $table->integer('item_id')->nullable(false)->change();
            $table->integer('package')->nullable(false)->change();
            $table->integer('color')->nullable(false)->change();
            $table->decimal('price')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
            $table->decimal('sum')->nullable(false)->change();
        });
    }
}
