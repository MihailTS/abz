<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('position');
            $table->date('employmentDate');
            $table->decimal('salary',8,2);
            $table->unsignedInteger('head_id')->nullable();//президент компании может не иметь начальника
            $table->foreign('head_id')->references('id')->on('employees')->onDelete('SET NULL');
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
        /*Schema::table("employees",
        function($table) {
            $table->dropForeign('head_id');
        });*/
        Schema::dropIfExists('employees');
    }
}
