<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliderOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('effect_type')->nullable();
            $table->integer('navigation')->nullable();
            $table->integer('pagination_type')->nullable();
            $table->integer('verticle')->nullable();
            $table->integer('slidesPerView')->nullable();
            $table->integer('spaceBetween')->nullable();
            $table->integer('loop')->nullable();
            $table->integer('speed')->nullable();
            $table->integer('parallax')->nullable();
            $table->integer('autoplay')->nullable();
            $table->integer('autoplay_delay')->nullable();
            $table->integer('autoplay_disable_on_interaction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider_options');
    }
}
