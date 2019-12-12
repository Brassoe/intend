<?php

namespace App\Module\Fridge\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fridge_item_parents', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name', 128);
			$table->string('comment', 128)->nullable();
			$table->bigInteger('category_id', false, true);
			$table->string('user_id', 40);

			$table->foreign('category_id')->references('id')->on('fridge_categories')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fridge_item_parents');
    }
}

