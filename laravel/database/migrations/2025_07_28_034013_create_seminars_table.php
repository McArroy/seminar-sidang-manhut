<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up() : void
	{
		Schema::create("seminars", function(Blueprint $table)
		{
			$table->string("seminarid")->primary();
			$table->string("useridnumber");
			$table->string("studyprogram");
			$table->string("department");
			$table->string("supervisor1");
			$table->string("supervisor2");
			$table->string("date");
			$table->string("time");
			$table->string("place");
			$table->string("title");
			$table->longText("comment")->nullable();
			$table->tinyInteger("status")->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		Schema::dropIfExists("seminars");
	}
};