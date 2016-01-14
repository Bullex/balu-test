<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->integer('parent_id')->nullable()->index();
			$table->integer('inner_id')->nullable();
			$table->integer('lft')->nullable()->index();
			$table->integer('rgt')->nullable()->index();
			$table->integer('depth')->nullable();
			$table->timestamps();
		});

		// Insert some stuff
    DB::table('categories')->insert(
        array(
            'name' => 'Root node 1',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        )
    );
    DB::table('categories')->insert(
        array(
            'name' => 'Root node 2',
            'lft' => 3,
            'rgt' => 4,
            'depth' => 0,
        )
    );
    DB::table('categories')->insert(
        array(
            'name' => 'Root node 3',
            'lft' => 5,
            'rgt' => 6,
            'depth' => 0,
        )
    );
	}

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('categories');
  }

}
