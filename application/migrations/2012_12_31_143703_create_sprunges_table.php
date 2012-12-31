<?php

class Create_Sprunges_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('sprunges', function($table)
        {
            $table->increments('id');
            $table->string('hash', 32);
            $table->text('content');

            $table->timestamps();
        });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sprunges');
	}

}