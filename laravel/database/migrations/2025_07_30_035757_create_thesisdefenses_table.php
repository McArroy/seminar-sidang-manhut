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
			$table->text("date");
			$table->text("time");
			$table->text("place");
			$table->text("title");
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