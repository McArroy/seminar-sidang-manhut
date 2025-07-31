<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ThesisdefenseController;

Route::middleware(
[
	"auth:sanctum",
	config("jetstream.auth_session"),
	"verified",
])->group(function()
{
	// student
	Route::prefix("student")->name("student.")->group(function()
	{
		// base
		Route::get("/dashboard", [PageController::class, "Dashboard"])->name("dashboard");

		Route::delete("/dashboard/seminar/{seminar}", [SeminarController::class, "Destroy"])->name("seminar.delete");

		Route::delete("/dashboard/thesisdefense/{thesisdefense}", [ThesisdefenseController::class, "Destroy"])->name("thesisdefense.delete");

		Route::get("/flow", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.flow");
		})->name("flow");

		Route::get("/registrationform", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.registrationform");
		})->name("registrationform");

		Route::post("/registrationform", function(Request $request)
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->Store($request);
			else
				return app()->make(ThesisdefenseController::class)->Store($request);
		})->name("registrationform");

		Route::get("/registrationform/letter", function(Request $request)
		{
			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->Created($request);
			else
				return app()->make(ThesisdefenseController::class)->Created($request);
		})->name("registrationletter");

		Route::get("/registrationform/letter/preview", function(Request $request)
		{
			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->RePreview($request);
			else
				return app()->make(ThesisdefenseController::class)->RePreview($request);
		})->name("registrationletterrepreview");

		Route::get("/requirements", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.requirements");
		})->name("requirements");

		Route::post("/requirements", function(Request $request)
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->UpdateLink($request);
			else
				return app()->make(ThesisdefenseController::class)->UpdateLink($request);
		})->name("requirements");

		Route::get("/thesisdefenseflow", function()
		{
			if (!Auth::check() || Auth::user()->userrole !== "student")
				return redirect("/");

			return view("student.thesisdefenseflow");
		})->name("thesisdefenseflow");

		Route::get("/schedule", [PageController::class, "Schedule"])->name("schedule");
	});
});