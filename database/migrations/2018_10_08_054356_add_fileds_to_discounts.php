<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsToDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->float('amount_fixed')->nullable();
            $table->boolean('for_all_user');
            $table->boolean('show_discount_percent')->nullable();
            $table->integer('discount_type');
            $table->float('amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn('amount_fixed');
            $table->dropColumn('for_all_user');
            $table->dropColumn('show_discount_percent');
            $table->dropColumn('discount_type');
            $table->float('amount')->nullable(false)->change();
        });
    }
}
