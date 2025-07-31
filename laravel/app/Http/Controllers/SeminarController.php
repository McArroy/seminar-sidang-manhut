<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
	public function GetAll()
	{
		return Seminar::all();
	}

	public function Index()
	{
		if (Auth::user()->userrole === "student")
		{
			$userId = Auth::user()->useridnumber;

			$dataseminar = $this->GetAll()->filter(function($seminar) use ($userId)
			{
				return $seminar->useridnumber === $userId;
			});

			return $dataseminar;
		}
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

		return redirect()->route("student.registrationletter", ["type" => "seminar"]);
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

		return redirect()->route("student.registrationletter", ["type" => "seminar"])->with("toast_success", "Seminar Berhasil Dibuat");
	}

	public function Update(Request $request, Seminar $seminar)
	{
		$request->validate(
		[
			"title" => "required|string|max:255",
			"content" => "required|string"
		]);

		$seminar->update($request->only("title", "content"));

		return redirect()->route("posts.index")->with("toast_success", "Post updated.");
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
			return redirect()->back()->with("toast_info", "Semua Seminar Anda Sudah Lengkap");

		$seminarToUpdate->update(
		[
			"link" => $validated["link"],
		]);

		return redirect()->route("student.dashboard")->with("toast_success", "Link Dokumen Seminar Berhasil Ditambahkan");
	}

	public function Destroy(Seminar $seminar)
	{
		$seminar->delete();

		if (Auth::user()->userrole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Seminar Berhasil Dihapus");
	}
}