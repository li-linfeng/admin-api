<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('roles', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('role_permission_rels', function (Blueprint $table) {
            $table->id('id');
            $table->string('role_id');
            $table->string('permission');
            $table->timestamps();
        });

        Schema::create('user_role_rels', function (Blueprint $table) {
            $table->id('id');
            $table->string('role_id');
            $table->string('user_id');
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

        Schema::drop('roles');
        Schema::drop('user_role_rel');
        Schema::drop('role_permission_rel');
    }
}
