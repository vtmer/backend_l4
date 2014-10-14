<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroup extends Migration {

    protected $tableName = 'groups';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function($table) {
            $table->increments('id');
            $table->string('name', 100);

            $table->engine = 'InnoDB';
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop($this->tableName);
	}

}
