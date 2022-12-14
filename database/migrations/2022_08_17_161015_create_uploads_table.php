<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string("type")->default("image")->comment("文件类型,image, file");
            $table->string("path")->default("")->comment("文件路径");
            $table->string("filename")->default("")->comment("文件名");
            $table->string("source_type")->default("sale_request")->comment("所属资源类型");
            $table->integer("source_id")->default(0)->comment("所属资源id");
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
        Schema::dropIfExists('uploads');
    }
}
