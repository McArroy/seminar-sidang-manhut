<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{	
	public function Index()
	{
		if (Auth::user()->userrole === "student")
		{
			$userId = Auth::user()->useridnumber;

			$data = Seminar::all()->filter(function($seminar) use ($userId)
			{
				return $seminar->useridnumber === $userId;
			});

			return view("student.dashboard", compact("data"));
		}
	}

	public function Create()
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");
		
		return view("student.registrationform");
	}

	public function Created(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$data = session()->pull("validated_data_letter", []);

		return view("student.registrationletter", ["data" => $data]);
	}

	public function RePreview(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$data = $request->all();

		session(["validated_data_letter" => $data]);

		return redirect()->route("student.registrationletter");
	}

	public function Store(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$validated = $request->validate(
		[
			"useridnumber" => "required|string|max:33",
			"studyprogram" => "required|string|max:127",
			"department" => "required|string|max:127",
			"supervisor1" => "required|string|max:255",
			"supervisor2" => "required|string|max:255",
			"date" => "required|string|max:17",
			"time" => "required|string|max:17",
			"place" => "required|string|max:255",
			"title" => "required|string|max:255",
			"link" => "nullable|string|max:1000",
			"comment" => "nullable|string|max:1000",
			"status" => "nullable|integer|in:0,1"
		]);

		$validated["seminarid"] = (string)Str::uuid();

		Seminar::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter")->with("toast", "Post created.");
	}

	public function Show(Seminar $seminar)
	{
		return view("posts.show", compact("seminar"));
	}

	public function Edit(Seminar $seminar)
	{
		return view("posts.edit", compact("post"));
	}

	public function Update(Request $request, Seminar $seminar)
	{
		$request->validate(
		[
			"title" => "required|string|max:255",
			"content" => "required|string"
		]);

		$seminar->update($request->only("title", "content"));

		return redirect()->route("posts.index")->with("success", "Post updated.");
	}

	public function UpdateLink(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$validated = $request->validate(
		[
			"link" => "required|string|max:1000"
		]);

		$userId = Auth::user()->useridnumber;

		$seminars = Seminar::all()->filter(function($seminar) use ($userId)
		{
			return $seminar->useridnumber === $userId;
		});

		$seminarToUpdate = $seminars->filter(function($s)
		{
			return empty($s->link);
		})->sortBy("created_at")->first();

		if (!$seminarToUpdate)
			return redirect()->back()->with("error", "No seminar with an empty link found for your account.");

		$seminarToUpdate->update(
		[
			"link" => $validated["link"],
		]);

		return redirect()->route("student.dashboard")->with("success", "Link successfully updated.");
	}

	public function Destroy(Seminar $seminar)
	{
		$seminar->delete();

		if (Auth::user()->userrole === "student")
			return redirect()->route("student.dashboard")->with("toast", "Seminar Deleted");
	}
}