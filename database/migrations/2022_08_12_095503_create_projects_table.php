<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_no')->comment("项目编号")->default(" ");
            $table->string("name")->comment("项目名");
            $table->integer("user_id")->comment("项目创建人")->default(0);
            $table->string("customer_name")->comment("客户名称");
            $table->string("product_name")->comment("产品名称");
            $table->json("project_time")->comment("项目时间节点")->nullable();
            $table->string("cost")->comment("项目预估金额")->default(" ");
            $table->string("status")->comment("项目状态, continue 进行中, cancel 取消, finish 结束 ")->default("continue");
            $table->text("close_reason")->comment("丢单原因")->nullable();
            $table->text("compare_info")->comment("竞品信息")->nullable();
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
        Schema::dropIfExists('projects');
    }
}
