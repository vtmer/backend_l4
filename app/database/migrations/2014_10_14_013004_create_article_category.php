<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCategory extends Migration {

    protected $tableName = 'article_category';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create($this->tableName, function ($table) {
            $table->integer('article_id');
            $table->integer('category_id');
            $table->primary(array('article_id', 'category_id'));

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
