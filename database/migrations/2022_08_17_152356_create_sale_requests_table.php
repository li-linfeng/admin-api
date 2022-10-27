<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_requests', function (Blueprint $table) {
            $table->id();
            $table->string("project_no")->default("")->comment("项目编号");
            $table->string("product_type")->default("")->comment("产品类型");
            $table->string("handle_type")->default("")->comment("第一个产品类型");
            $table->string("customer_name")->default("")->comment("客户名称");
            $table->string("device_name")->nullable()->default("")->comment("设备名称");
            $table->string("driver_type")->nullable()->default("")->comment("驱动类型");
            $table->string("driver_power")->nullable()->default("")->comment("驱动功率");
            $table->string("rpm")->nullable()->default("")->comment("实际转速");
            $table->string("torque")->nullable()->default("")->comment("设定扭矩");
            $table->string("shaft_one_diameter_tolerance")->nullable()->default("")->comment("轴1直径及公差");
            $table->string("shaft_two_diameter_tolerance")->nullable()->default("")->comment("轴2直径及公差");
            $table->string("shaft_one_match_distance")->nullable()->default("")->comment("轴1配合段长度");
            $table->string("shaft_two_match_distance")->nullable()->default("")->comment("轴2配合段长度");
            $table->string("shaft_space_distance")->nullable()->default("")->comment("轴端面间距");
            $table->string("status")->nullable()->default("open")->comment("状态");
            $table->text("remark")->nullable()->comment("备注");
            $table->integer("user_id")->comment("所属用户id")->default(0);
            $table->string("expect_time")->comment("期望货期")->default("");
            $table->text("return_reason")->nullable()->comment("退回原因");
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
        Schema::dropIfExists('sale_requests');
    }
}
