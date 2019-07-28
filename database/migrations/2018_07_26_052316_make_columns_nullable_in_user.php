<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColumnsNullableInUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('iscompany')->nullable()->change();
            $table->string('company_title')->nullable()->change();
            $table->string('company_code')->nullable()->change();
            $table->string('company_vatcode')->nullable()->change();
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
            $table->dropColumn('iscompany');
            $table->dropColumn('company_title');
            $table->dropColumn('company_code');
            $table->dropColumn('company_vatcode');
        });
    }
}
