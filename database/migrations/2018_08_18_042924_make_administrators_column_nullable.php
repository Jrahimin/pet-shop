<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAdministratorsColumnNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('administrators', function (Blueprint $table) {
            $table->string('user')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('surname')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('telephone')->nullable()->change();
            $table->integer('active')->nullable()->change();
            $table->integer('status')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('remember_token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administrators', function (Blueprint $table) {
            $table->string('user')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->string('surname')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('telephone')->nullable(false)->change();
            $table->integer('active')->nullable(false)->change();
            $table->integer('status')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->string('remember_token')->nullable(false)->change();
    });
    }
}
