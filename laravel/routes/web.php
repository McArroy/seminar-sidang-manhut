<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ThesisdefenseController;
use App\Http\Controllers\UserController;

Route::get("/", function()
{
	if (!Auth::check() || !Auth::user()->userrole)
		return redirect("/login");

	if (Auth::user()->userrole === "admin")
		return redirect()->route("admin.dashboard");
	else if (Auth::user()->userrole === "student")
		return redirect()->route("admin.dashboard");
});

// admin
Route::middleware(
[
	"auth:sanctum",
	config("jetstream.auth_session"),
	"verified",
	"admin"
])->group(function()
{
	Route::prefix("admin")->name("admin.")->group(function()
	{
		Route::get("/dashboard", [PageController::class, "Dashboard"])->name("dashboard");

		Route::get("/students", [PageController::class, "Students"])->name("students");

		Route::post("/students", [UserController::class, "StoreStudents"])->name("students.add");

		Route::post("/students/update/{user}", [UserController::class, "UpdateStudents"])->name("students.update");

		Route::delete("/students/delete/{user}", [UserController::class, "DestroyStudents"])->name("students.delete");

		Route::get("/lecturers", [PageController::class, "Lecturers"])->name("lecturers");

		Route::post("/lecturers", [UserController::class, "StoreLecturers"])->name("lecturers.add");

		Route::post("/lecturers/update/{user}", [UserController::class, "UpdateLecturers"])->name("lecturers.update");

		Route::delete("/lecturers/delete/{user}", [UserController::class, "DestroyLecturers"])->name("lecturers.delete");

		Route::get("/seminars", [PageController::class, "Seminars"])->name("seminars");

		Route::post("/seminars/accept/{seminar}", [SeminarController::class, "Accept"])->name("seminars.accept");

		Route::post("/seminars/revision/{seminar}", [SeminarController::class, "Comment"])->name("seminars.revision");

		Route::post("/seminars/reject/{seminar}", [SeminarController::class, "Reject"])->name("seminars.reject");

		Route::get("/thesisdefenses", [PageController::class, "Thesisdefenses"])->name("thesisdefenses");

		Route::post("/thesisdefenses/accept/{thesisdefense}", [ThesisdefenseController::class, "Accept"])->name("thesisdefenses.accept");

		Route::post("/thesisdefenses/revision/{thesisdefense}", [ThesisdefenseController::class, "Comment"])->name("thesisdefenses.revision");

		Route::post("/thesisdefenses/reject/{thesisdefense}", [ThesisdefenseController::class, "Reject"])->name("thesisdefenses.reject");

		Route::get("/announcements", function(Request $request)
		{
			if (!in_array($_GET["type"] ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return app()->make(PageController::class)->Announcements($request);
		})->name("announcements");

		Route::post("/announcements/{seminar}", [SeminarController::class, "UpdateLink"])->name("announcements.seminar.add");

		Route::post("/announcements/{thesisdefense}", [ThesisdefenseController::class, "UpdateLink"])->name("announcements.thesisdefense.add");

		Route::get("/schedule", [PageController::class, "Schedule"])->name("schedule");
	});
});

// student
Route::middleware(
[
	"auth:sanctum",
	config("jetstream.auth_session"),
	"verified",
	"student"
])->group(function()
{
	Route::prefix("student")->name("student.")->group(function()
	{
		Route::get("/dashboard", [PageController::class, "Dashboard"])->name("dashboard");

		Route::delete("/dashboard/delete/seminar/{seminar}", [SeminarController::class, "Destroy"])->name("seminar.delete");

		Route::delete("/dashboard/delete/thesisdefense/{thesisdefense}", [ThesisdefenseController::class, "Destroy"])->name("thesisdefense.delete");

		Route::get("/flow", function()
		{
			return view("student.flow");
		})->name("flow");

		Route::get("/registrationform", function()
		{
			return view("student.registrationform");
		})->name("registrationform");

		Route::post("/registrationform", function(Request $request)
		{
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
			return view("student.requirements");
		})->name("requirements");

		Route::post("/requirements", function(Request $request)
		{
			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->UpdateLink($request);
			else
				return app()->make(ThesisdefenseController::class)->UpdateLink($request);
		})->name("requirements");

		Route::get("/thesisdefenseflow", function()
		{
			return view("student.thesisdefenseflow");
		})->name("thesisdefenseflow");

		Route::get("/schedule", [PageController::class, "Schedule"])->name("schedule");
	});
});