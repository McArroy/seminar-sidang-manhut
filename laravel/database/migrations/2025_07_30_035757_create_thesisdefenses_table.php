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
		Schema::create("thesisdefenses", function(Blueprint $table)
		{
			$table->string("thesisdefenseid")->primary();
			$table->text("useridnumber");
			$table->text("semester");
			$table->text("address");
			$table->text("supervisor1");
			$table->text("supervisor2");
			$table->date("date");
			$table->string("time");
			$table->text("place");
			$table->string("title");
			$table->text("link")->nullable();
			$table->text("comment")->nullable();
			$table->tinyInteger("status")->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		Schema::dropIfExists("thesisdefenses");
	}
};