<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\LetterController;
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
		// Dashboard
		Route::get("/dashboard", [PageController::class, "Dashboard"])->name("dashboard");

		// Admin
		Route::get("/admins", [PageController::class, "Admins"])->name("admins");
		Route::post("/admins", [UserController::class, "StoreAdmins"])->name("admins.add");
		Route::post("/admins/update/{userid}", [UserController::class, "UpdateAdmins"])->name("admins.update");
		Route::delete("/admins/delete/{userid}", [UserController::class, "DestroyAdmins"])->name("admins.delete");

		// Student
		Route::get("/students", [PageController::class, "Students"])->name("students");
		Route::post("/students", [UserController::class, "StoreStudents"])->name("students.add");
		Route::post("/students/update/{userid}", [UserController::class, "UpdateStudents"])->name("students.update");
		Route::delete("/students/delete/{userid}", [UserController::class, "DestroyStudents"])->name("students.delete");

		// Lecturer
		Route::get("/lecturers", [PageController::class, "Lecturers"])->name("lecturers");
		Route::post("/lecturers", [UserController::class, "StoreLecturers"])->name("lecturers.add");
		Route::post("/lecturers/update/{userid}", [UserController::class, "UpdateLecturers"])->name("lecturers.update");
		Route::delete("/lecturers/delete/{userid}", [UserController::class, "DestroyLecturers"])->name("lecturers.delete");

		// Room
		Route::get("/rooms", [PageController::class, "Rooms"])->name("rooms");
		Route::post("/rooms", [RoomController::class, "Store"])->name("rooms.add");
		Route::post("/rooms/update/{roomid}", [RoomController::class, "Update"])->name("rooms.update");
		Route::delete("/rooms/delete/{roomid}", [RoomController::class, "Destroy"])->name("rooms.delete");

		// Academic
		Route::get("/academics", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return app()->make(PageController::class)->Academics($request);
		})->name("academics");
		Route::get("/academics/comment/{academicid}", [AcademicController::class, "GetCommentById"])->name("academics.comment");
		Route::post("/academics/accept/{academicid}", [AcademicController::class, "Accept"])->name("academics.accept");
		Route::post("/academics/revision/{academicid}", [AcademicController::class, "Comment"])->name("academics.revision");
		Route::post("/academics/reject/{academicid}", [AcademicController::class, "Reject"])->name("academics.reject");

		// Announcement
		Route::get("/announcements", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return app()->make(PageController::class)->Announcements($request);
		})->name("announcements");
		Route::get("/announcements/letter/{academicid}", [LetterController::class, "GetValuesByAcademicId"])->name("announcements.letter");
		Route::post("/announcements/add/{academicid}", [LetterController::class, "Store"])->name("announcements.letter.add");
		Route::post("/announcements/update/{academicid}", [LetterController::class, "Update"])->name("announcements.letter.update");
		Route::post("/announcements/print/{academicid}", [LetterController::class, "Print"])->name("announcements.letter.print");

		// Sechedule
		Route::get("/schedule", [PageController::class, "Schedule"])->name("schedule");
		Route::delete("/schedule/{academicid}", [PageController::class, "ScheduleDestroy"])->name("schedule.delete");
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
		// Dashboard
		Route::get("/dashboard", [PageController::class, "Dashboard"])->name("dashboard");
		Route::delete("/dashboard/delete/{academicid}", [AcademicController::class, "Destroy"])->name("academic.delete");

		// Flow
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

			$dataLecturers = app()->make(UserController::class)->GetLecturers(true)->pluck("username", "useridnumber")->mapWithKeys(fn($v, $k) => [$k . " - " . $v => $v])->toArray();
			$dataTime = app()->make(AcademicController::class)->GetDataTime($request->query("type"));

			$dataTimeAssoc = [];
			foreach ($dataTime as $time)
			{
				$dataTimeAssoc[$time] = $time;
			}

			$dataRooms = app()->make(RoomController::class)->Index($request);

			return view("student.registrationform", ["dataLecturers" => $dataLecturers, "dataTime" => $dataTimeAssoc, "dataRooms" => $dataRooms]);
		})->name("registrationform");

		Route::post("/registrationform", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return app()->make(AcademicController::class)->Store($request);
		})->name("registrationform");

		Route::get("/registrationform/letter", function(Request $request)
		{
			if (!in_array($request->query("type") ?? null, ["seminar", "thesisdefense"]))
			{
				header("Location: " . url()->current() . "?type=seminar");
				exit;
			}

			return app()->make(AcademicController::class)->Created($request);
		})->name("registrationletter");

		Route::get("/registrationform/letter/preview/{academicid}", [AcademicController::class, "RePreview"])->name("registrationletterrepreview");

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

			return app()->make(AcademicController::class)->UpdateLink($request);
		})->name("requirements");

		Route::get("/schedule", [PageController::class, "Schedule"])->name("schedule");
	});
});