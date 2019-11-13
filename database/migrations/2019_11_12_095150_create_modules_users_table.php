<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_users', function (Blueprint $table) {
			$table->bigInteger('fk_module', false, true);
			$table->string('fk_user', 40);

			$table->foreign('fk_module')->references('id')->on('modules')->onDelete('cascade');
			$table->foreign('fk_user')->references('id')->on('users')->onDelete('cascade');

			$table->primary(['fk_module', 'fk_user']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules_users');
    }
}
