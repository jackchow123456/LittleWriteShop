<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('店铺名称');
            $table->tinyInteger('status')->default('0')->comment('店铺状态{0:关闭，1:开启}');
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
        Schema::dropIfExists('shop_stores');
    }
}
