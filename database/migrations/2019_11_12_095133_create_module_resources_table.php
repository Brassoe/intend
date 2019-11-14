<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('path', 256);
			$table->enum('type', ['file', 'screenshot']);
			$table->bigInteger('fk_module', false, true);

			$table->foreign('fk_module')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_resources');
    }
}
