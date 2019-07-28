<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeOrdersColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('userid')->nullable()->change();
            $table->integer('paid')->nullable()->change();
            $table->integer('paidsum')->nullable()->change();
            $table->integer('paymentdate')->nullable()->change();
            $table->string('paymentbank')->nullable()->change();
            $table->string('paymentcurrency')->nullable()->change();
            $table->string('paymentstatus')->nullable()->change();
            $table->string('response', 2000)->nullable()->change();
            $table->decimal('items_sum')->nullable()->change();
            $table->decimal('final_sum')->nullable()->change();
            $table->decimal('totalweight')->nullable()->change();
            $table->string('delivery_type')->nullable()->change();
            $table->decimal('delivery_price')->nullable()->change();
            $table->integer('payondel')->nullable()->change();
            $table->decimal('payondel_price')->nullable()->change();
            $table->integer('veni_daynr')->nullable()->change();
            $table->integer('veni_totalnr')->nullable()->change();
            $table->string('veni_fullnr')->nullable()->change();
            $table->integer('veni_status')->nullable()->change();
            $table->string('veni_response' , 2000)->nullable()->change();
            $table->string('veni_errors')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('surname')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('zip_code')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->integer('needvat')->nullable()->change();
            $table->string('company_title')->nullable()->change();
            $table->string('company_code')->nullable()->change();
            $table->string('company_vatcode')->nullable()->change();
            $table->string('delivery_notes', 2000)->nullable()->change();
            $table->string('delivery_city')->nullable()->change();
            $table->string('delivery_address')->nullable()->change();
            $table->string('delivery_zip_code')->nullable()->change();
            $table->integer('hidden')->nullable()->change();
            $table->string('ordercode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('userid');
            $table->dropColumn('paid');
            $table->dropColumn('paidsum');
            $table->dropColumn('paymentdate');
            $table->dropColumn('paymentbank');
            $table->dropColumn('paymentcurrency');
            $table->dropColumn('paymentstatus');
            $table->dropColumn('response');
            $table->dropColumn('items_sum');
            $table->dropColumn('final_sum');
            $table->dropColumn('totalweight');
            $table->dropColumn('delivery_type');
            $table->dropColumn('delivery_price');
            $table->dropColumn('payondel');
            $table->dropColumn('payondel_price');
            $table->dropColumn('veni_daynr');
            $table->dropColumn('veni_totalnr');
            $table->dropColumn('veni_fullnr');
            $table->dropColumn('veni_status');
            $table->dropColumn('veni_response');
            $table->dropColumn('veni_errors');
            $table->dropColumn('name');
            $table->dropColumn('surname');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('zip_code');
            $table->dropColumn('city');
            $table->dropColumn('needvat');
            $table->dropColumn('company_title');
            $table->dropColumn('company_code');
            $table->dropColumn('company_vatcode');
            $table->dropColumn('delivery_notes');
            $table->dropColumn('delivery_city');
            $table->dropColumn('delivery_address');
            $table->dropColumn('delivery_zip_code');
            $table->dropColumn('hidden');
            $table->dropColumn('ordercode');
        });
    }
}
