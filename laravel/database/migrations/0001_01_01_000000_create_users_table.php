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
		Schema::create("users", function(Blueprint $table)
		{
			$table->string("userid")->primary();
			$table->string("useridnumber")->unique();
			$table->text("username");
			$table->text("userrole");
			$table->string("password");
			$table->timestamps();
		});

		Schema::create("sessions", function(Blueprint $table)
		{
			$table->string("id")->primary();
			$table->string("user_id")->nullable()->index();
			$table->foreign("user_id")->references("userid")->on("users")->onDelete("cascade");
			$table->string("ip_address", 45)->nullable();
			$table->text("user_agent")->nullable();
			$table->longText("payload");
			$table->integer("last_activity")->index();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() : void
	{
		Schema::dropIfExists("users");
		Schema::dropIfExists("sessions");
	}
};