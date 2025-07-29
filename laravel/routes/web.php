<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SeminarController;

Route::middleware(
[
	"auth:sanctum",
	config("jetstream.auth_session"),
	"verified",
])->group(function()
{
	Route::get("/", function()
	{
		return view("welcome");
	});

	Route::get("/dashboard", function()
	{
		return view("dashboard");
	})->name("dashboard");

	// student
	Route::prefix("student")->name("student.")->group(function()
	{
		Route::get("/dashboard", [SeminarController::class, "Index"])->name("dashboard");
		Route::delete("/seminar/{seminar}", [SeminarController::class, "Destroy"])->name("seminar.delete");

		Route::get("/flow", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.flow");
		})->name("flow");
		
		Route::get("/registrationform", [SeminarController::class, "Create"])->name("registrationform");

		Route::get("/registrationform/letter", [SeminarController::class, "Created"])->name("registrationletter");
		Route::get("/registrationform/letter/preview", [SeminarController::class, "RePreview"])->name("registrationletterrepreview");
		
		Route::post("/registrationform", [SeminarController::class, "Store"])->name("registrationform");
		
		Route::get("/requirements", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.requirements");
		})->name("requirements");

		Route::post("/requirements", [SeminarController::class, "UpdateLink"])->name("requirements");
		
		Route::get("/thesisdefenseflow", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.thesisdefenseflow");
		})->name("thesisdefenseflow");
		
		Route::get("/schedule", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.schedule");
		})->name("schedule");
	});
});