<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitColumnInPackageAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_attributes', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->string('unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_attributes', function (Blueprint $table) {
            $table->integer('unit_id');
            $table->dropColumn('unit');
        });
    }
}
