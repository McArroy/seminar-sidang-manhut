<?php

namespace App\Http\Controllers;

use App\Models\Thesisdefense;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ThesisdefenseController extends Controller
{
	public function GetAll()
	{
		return Thesisdefense::all();
	}

	public function Index()
	{
		if (Auth::user()->userrole === "student")
		{
			$userId = Auth::user()->useridnumber;

			$datathesisdefense = $this->GetAll()->filter(function($thesisdefense) use ($userId)
			{
				return $thesisdefense->useridnumber === $userId;
			});

			return $datathesisdefense;
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

		return redirect()->route("student.registrationletter", ["type" => "thesisdefense"]);
	}

	public function Store(Request $request)
	{
		if (!Auth::check() || Auth::user()->userrole !== "student")
			return redirect("/");

		$validated = $request->validate(
		[
			"useridnumber" => "required|string|max:33",
			"semester" => "required|string|max:33",
			"address" => "required|string|max:1000",
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

		$validated["thesisdefenseid"] = (string)Str::uuid();

		Thesisdefense::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter", ["type" => "thesisdefense"])->with("toast_success", "Sidang Akhir Berhasil Dibuat");
	}

	public function Update(Request $request, Thesisdefense $thesisdefense)
	{
		$request->validate(
		[
			"title" => "required|string|max:255",
			"content" => "required|string"
		]);

		$thesisdefense->update($request->only("title", "content"));

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

		$thesisdefenses = Thesisdefense::all()->filter(function($thesisdefense) use ($userId)
		{
			return $thesisdefense->useridnumber === $userId;
		});

		$thesisdefenseToUpdate = $thesisdefenses->filter(function($s)
		{
			return empty($s->link);
		})->sortBy("created_at")->first();

		if (!$thesisdefenseToUpdate)
			return redirect()->back()->with("toast_info", "Semua Sidang Akhir Anda Sudah Lengkap");

		$thesisdefenseToUpdate->update(
		[
			"link" => $validated["link"],
		]);

		return redirect()->route("student.dashboard")->with("toast_success", "Link Dokumen Sidang Akhir Berhasil Ditambahkan");
	}

	public function Destroy(Thesisdefense $thesisdefense)
	{
		$thesisdefense->delete();

		if (Auth::user()->userrole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Sidang Akhir Berhasil Dihapus");
	}
}