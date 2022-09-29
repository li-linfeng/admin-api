<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handlers', function (Blueprint $table) {
            $table->id();
            $table->string('module')->comment('模块')->default('');
            $table->string('module_cn')->comment('模块中文名')->default('');
            $table->string('product_type')->comment('模块')->default('');
            $table->integer('handler_id')->comment('处理人id')->default(0);
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
        Schema::dropIfExists('handlers');
    }
}
