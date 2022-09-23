<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("name")->comment("名称");
            $table->string("name_cn")->comment("名称");
            $table->string("type")->comment("type")->default('');
            $table->string("code")->comment("series_code")->default('');
            $table->string("description")->comment("描述")->default('');
            $table->integer("handler_id")->comment("处理用户id")->default(0);
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
        Schema::dropIfExists('categories');
    }
}
