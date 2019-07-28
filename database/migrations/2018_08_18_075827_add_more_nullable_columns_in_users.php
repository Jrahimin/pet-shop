<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreNullableColumnsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('has_discount')->nullable()->change();
            $table->string('discount_from')->nullable()->change();
            $table->string('discount_to')->nullable()->change();
            $table->string('discount_type')->nullable()->change();
            $table->string('discount_percent')->nullable()->change();
            $table->string('discount_cat')->nullable()->change();
            $table->string('discount_items')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('has_discount')->nullable(false)->change();
            $table->string('discount_from')->nullable(false)->change();
            $table->string('discount_to')->nullable(false)->change();
            $table->string('discount_type')->nullable(false)->change();
            $table->string('discount_percent')->nullable(false)->change();
            $table->string('discount_cat')->nullable(false)->change();
            $table->string('discount_items')->nullable(false)->change();
        });
    }
}
