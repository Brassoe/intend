<?php

namespace App\Module\Fridge\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fridge_item_children', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->date('expiration_date', 128);
			$table->bigInteger('fridge_item_parent_id', false, true);

			$table->foreign('fridge_item_parent_id')->references('id')->on('fridge_item_parents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fridge_item_children');
    }
}

