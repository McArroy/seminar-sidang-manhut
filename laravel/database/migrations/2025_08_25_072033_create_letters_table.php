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
		Schema::create("letters", function(Blueprint $table)
		{
			$table->string("letterid")->primary();
			$table->text("letternumber")->unique();
			$table->date("letterdate");
			$table->text("moderator")->nullable();
			$table->text("supervisory_committee")->nullable();
			$table->text("external_examiner")->nullable();
			$table->text("chairman_session")->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		Schema::dropIfExists("letters");
	}
};