<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->default(0)->comment("订单id");
            $table->string('project_no')->default('')->comment("项目编号");
            $table->integer('amount')->default(0)->comment("数量");
            $table->string('product_name')->default("")->comment("产品型号");
            $table->string('product_price')->default("")->comment("产品价格");
            $table->string('product_date')->default("")->comment("产品货期");
            $table->integer('user_id')->default(0)->comment("创建者id");
            $table->string('handler_type')->default("")->comment("处理人type");
            $table->string('status')->default('open')->comment("状态");
            $table->string('material_number')->default("")->comment("物料编号");
            $table->string('category_name')->default("")->comment("物料类型");
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
        Schema::dropIfExists('order_items');
    }
}
