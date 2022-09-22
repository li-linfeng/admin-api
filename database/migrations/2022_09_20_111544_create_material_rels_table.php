<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialRelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_rels', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->comment('父组件的id')->default(0);
            $table->integer('child_id')->comment('子组件的id')->default(0);
            $table->integer('amount')->comment('需要子组件的数量')->default(0);
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
        Schema::dropIfExists('material_rels');
    }
}
