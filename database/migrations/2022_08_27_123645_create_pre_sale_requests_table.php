<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreSaleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_sale_requests', function (Blueprint $table) {
            $table->id();
            $table->string('sale_num')->default("")->comment("关联销售需求编码");
            $table->string('product_type')->default("")->comment("产品型号");
            $table->string('product_price')->default("")->comment("产品价格");
            $table->string('pre_pay')->default("")->comment("预付款金额");
            $table->string('product_date')->default("")->comment("产品货期");
            $table->text('remark')->nullable()->comment("备注");
            $table->integer('user_id')->default(0)->comment("处理工程师id");
            $table->string('status')->default("open")->comment("状态， published, return, finish");
            $table->integer('order_id')->default(0)->comment("关联的订单id");
            $table->integer('need_num')->default(0)->comment("需要的数量");
            $table->text('return_reason')->nullable()->comment("退回原因");
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
        Schema::dropIfExists('pre_sale_requests');
    }
}
