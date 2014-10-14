<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration {

    protected $tableName = 'users';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function($table) {
            $table->increments('id');
            $table->string('email', 50);
            $table->string('nickname', 50);
            $table->string('password', 128);
            $table->text('remember_token')->nullable();
            $table->timestamps();

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
