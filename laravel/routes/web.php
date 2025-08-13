<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ThesisdefenseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;

Route::get("/", function()
{
	if (!Auth::check() || !Auth::user()->userrole)
		return redirect("/login");

	if (Auth::user()->userrole === "admin" || Auth::user()->userrole === "student")
		return redirect()->route(Auth::user()->userrole . ".dashboard");
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

		Route::get("/seminars/comment/{seminarid}", [SeminarController::class, "GetCommentById"])->name("seminars.comment");

		Route::post("/seminars/accept/{seminar}", [SeminarController::class, "Accept"])->name("seminars.accept");

		Route::post("/seminars/revision/{seminar}", [SeminarController::class, "Comment"])->name("seminars.revision");

		Route::post("/seminars/reject/{seminar}", [SeminarController::class, "Reject"])->name("seminars.reject");

		Route::get("/thesisdefenses", [PageController::class, "Thesisdefenses"])->name("thesisdefenses");

		Route::get("/thesisdefenses/comment/{thesisdefenseid}", [ThesisdefenseController::class, "GetCommentById"])->name("thesisdefenses.comment");

		Route::post("/thesisdefenses/accept/{thesisdefense}", [ThesisdefenseController::class, "Accept"])->name("thesisdefenses.accept");

		Route::post("/thesisdefenses/revision/{thesisdefense}", [ThesisdefenseController::class, "Comment"])->name("thesisdefenses.revision");

		Route::post("/thesisdefenses/reject/{thesisdefense}", [ThesisdefenseController::class, "Reject"])->name("thesisdefenses.reject");

		Route::get("/announcements", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return app()->make(PageController::class)->Announcements($request);
		})->name("announcements");

		Route::post("/announcements/{seminar}", [SeminarController::class, "UpdateLink"])->name("announcements.seminar.add");

		Route::post("/announcements/{thesisdefense}", [ThesisdefenseController::class, "UpdateLink"])->name("announcements.thesisdefense.add");

		Route::get("/schedule", [PageController::class, "Schedule"])->name("schedule");

		// rooms
		Route::get("/rooms", [PageController::class, "Rooms"])->name("rooms");

		Route::post("/rooms", [RoomController::class, "Store"])->name("rooms.add");

		Route::post("/rooms/update/{room}", [RoomController::class, "Update"])->name("rooms.update");

		Route::delete("/rooms/delete/{room}", [RoomController::class, "Destroy"])->name("rooms.delete");
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

		Route::get("/flow", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return view("student.flow");
		})->name("flow");

		Route::get("/registrationform", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			$dataLecturers = app()->make(UserController::class)->GetLecturers();
			$dataRooms = app()->make(\App\Http\Controllers\RoomController::class)->Index($request);

			return view("student.registrationform", compact("dataLecturers", "dataRooms"));
		})->name("registrationform");

		Route::post("/registrationform", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->Store($request);
			else
				return app()->make(ThesisdefenseController::class)->Store($request);
		})->name("registrationform");

		Route::get("/registrationform/letter", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->Created($request);
			else
				return app()->make(ThesisdefenseController::class)->Created($request);
		})->name("registrationletter");

		Route::get("/registrationform/letter/preview", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			if ($request->query("type") === "seminar")
				return app()->make(SeminarController::class)->RePreview($request);
			else
				return app()->make(ThesisdefenseController::class)->RePreview($request);
		})->name("registrationletterrepreview");

		Route::get("/requirements", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return view("student.requirements");
		})->name("requirements");

		Route::post("/requirements", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

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