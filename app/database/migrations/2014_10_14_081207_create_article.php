<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticle extends Migration {

    protected $tableName = 'articles';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function ($table) {
            $table->increments('id');

            $table->text('title');
            $table->text('content');
            $table->mediumText('description')->nullable();

            $table->integer('view')->default(0);
            $table->integer('sort')->default(0);
            $table->integer('author_id');
            $table->integer('end_edit_author_id');

            $table->boolean('draft')->default(false);

            $table->softDeletes();
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
