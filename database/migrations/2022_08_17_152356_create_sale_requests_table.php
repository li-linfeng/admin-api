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
            $table->string("sale_num")->default("")->comment("需求编号");
            $table->string("product_type")->default("")->comment("产品类型");
            $table->string("customer_type")->default("")->comment("客户性质");
            $table->string("device_name")->default("")->comment("设备名称");
            $table->string("driver_type")->default("")->comment("驱动类型");
            $table->string("driver_power")->default("")->comment("驱动功率");
            $table->string("rpm")->default("")->comment("实际转速");
            $table->string("torque")->default("")->comment("设定扭矩");
            $table->string("shaft_one_diameter_tolerance")->default("")->comment("轴1直径及公差");
            $table->string("shaft_two_diameter_tolerance")->default("")->comment("轴2直径及公差");
            $table->string("shaft_one_match_distance")->default("")->comment("轴1配合段长度");
            $table->string("shaft_two_match_distance")->default("")->comment("轴2配合段长度");
            $table->string("shaft_space_distance")->default("")->comment("轴端面间距");
            $table->string("status")->default("open")->comment("状态");
            $table->string("upload_ids")->default("")->comment("附件ids");
            $table->text("remark")->comment("备注");
            $table->integer("user_id")->comment("所属用户id");
            $table->integer("handle_user_id")->comment("负责此需求的工程师id");
            $table->integer("leader_id")->comment("负责此产品的领导id");
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
