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
            $table->string('sale_id')->default("")->comment("销售需求编号");
            $table->string('product_name')->default("")->comment("产品型号");
            $table->string('category')->default("")->comment("产品所属类型");
            $table->string('product_price')->default("")->comment("产品价格");
            $table->string('product_date')->default("")->comment("产品货期");
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
