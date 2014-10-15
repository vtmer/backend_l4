<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideo extends Migration {

    protected $tableName = 'videos';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function ($table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->integer('code')->unqiue();
            $table->string('link', 1000);

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
