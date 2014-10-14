<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupFieldToUser extends Migration {

    protected $tableName = 'users';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table($this->tableName, function ($table) {
            $table->integer('group_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table($this->tableName, function ($table) {
            $table->dropColumn('group_id');
        });
	}

}
