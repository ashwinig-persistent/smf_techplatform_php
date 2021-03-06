<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('organisations')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles')
            ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('organisations')
            ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('org_id');
            $table->dropColumn('role_id');
        });
    }
}
