<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string("label")->comment("名称");
            $table->string("type")->comment("类型,现有分类： assembly  装配体， sub_assembly 子装配体， component 零件");
            $table->string("property")->comment("类型,现有分类 空白；机加工；毛坯；标准件；五金件；橡塑件");
            $table->text("description")->nullable()->comment("描述");
            $table->integer("has_child")->default(1)->comment("是否有子分类");
            $table->integer("category_id")->default(0)->comment("分类id");
            $table->integer("is_show")->default(0)->comment("默认不展示");
            $table->integer("status")->default(0)->comment("初始化时未绑定子组件");
            $table->timestamps();
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
