<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoviewpagerImage extends Migration {

    protected $tableName = 'photoviewpager_image';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function ($table) {
            $table->increments('id');
            $table->integer('photoviewpager_id');
            $table->string('imagename', 1000);
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
