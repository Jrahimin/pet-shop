<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToSeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_nustatymai', function (Blueprint $table) {
            $table->string('meta_key')->nullable()->change();
            $table->string('meta_desc')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_nustatymai', function (Blueprint $table) {
            $table->string('meta_key')->nullable(false)->change();
            $table->string('meta_desc')->nullable(false)->change();
        });
    }
}
