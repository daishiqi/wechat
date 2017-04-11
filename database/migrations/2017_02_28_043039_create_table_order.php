<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('oid');
            $table->string('ordsn');
            $table->integer('uid');
            $table->string('openid',32);
            $table->string('xm' , 15);
            $table->string('address' , 30);
            $table->string('tel' , 11);
            $table->float('money' , 7,2);
            $table->tinyinteger('ispay');
            $table->integer('ordtime')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
