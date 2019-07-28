<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeDarbaiColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darbai', function (Blueprint $table) {
            $table->string('foto2')->nullable()->change();
            $table->decimal('svoris')->nullable()->change();
            $table->decimal('price')->nullable()->change();


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
            $table->dropColumn('foto2');
            $table->dropColumn('svoris');
            $table->dropColumn('price');


        });
    }
}
