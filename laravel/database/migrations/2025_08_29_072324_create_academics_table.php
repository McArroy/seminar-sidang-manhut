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
		Schema::create("academics", function(Blueprint $table)
		{
			$table->string("academicid")->primary();
			$table->string("academictype");
			$table->text("useridnumber");
			$table->text("studyprogram")->nullable();
			$table->text("department")->nullable();
			$table->text("semester")->nullable();
			$table->text("address")->nullable();
			$table->text("lecturers");
			$table->date("date");
			$table->string("time");
			$table->text("room");
			$table->string("title");
			$table->text("link")->nullable();
			$table->text("comment")->nullable();
			$table->tinyInteger("is_accepted")->nullable();
			$table->tinyInteger("is_completed")->nullable()->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		Schema::dropIfExists("academics");
	}
};