<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_num')->default('')->comment("订单编号");
            $table->string('status')->default('open')->comment("订单状态");
            $table->integer('user_id')->default(0)->comment("订单创建人");
            $table->string('total_pay')->default("")->comment("总金额");
            $table->string('customer_name')->default("")->comment("客户名称");
            $table->string('total_pre_pay')->default("")->comment("总预付款");
            $table->string('upload_ids')->default("")->comment("附件");
            $table->text('remark')->nullable()->comment("备注");
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
        Schema::dropIfExists('orders');
    }
}
