<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
	private string $userId;
	private string $userRole;

	public function __construct()
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;
	}

	public function GetAll()
	{
		return Seminar::all();
	}

	public function Index()
	{
		if ($this->userRole === "admin")
		{
			$dataseminar = $this->GetAll();
		}
		else if ($this->userRole === "student")
		{
			$userId = $this->userId;

			$dataseminar = $this->GetAll()->filter(function($seminar) use ($userId)
			{
				return $seminar->useridnumber === $userId;
			});
		}

		return $dataseminar;
	}

	public function Created(Request $request)
	{
		$data = session()->pull("validated_data_letter", []);

		return view("student.registrationletter", ["data" => $data]);
	}

	public function RePreview(Request $request)
	{
		$data = $request->all();

		session(["validated_data_letter" => $data]);

		return redirect()->route("student.registrationletter", ["type" => "seminar"]);
	}

	public function Store(Request $request)
	{
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

	public function UpdateLink(Request $request)
	{
		$validated = $request->validate(
		[
			"link" => "required|string|max:1000"
		]);

		$userId = $this->userId;

		$seminars = $this->GetAll()->filter(function($seminar) use ($userId)
		{
			return $seminar->useridnumber === $userId;
		});

		$seminarToUpdate = $seminars->filter(function($s)
		{
			return empty($s->link);
		})->sortBy("created_at")->first();

		if (!$seminarToUpdate)
			return redirect()->back()->with("toast_info", "Semua Data Seminar Anda Sudah Lengkap");

		$seminarToUpdate->update(
		[
			"link" => $validated["link"]
		]);

		return redirect()->route("student.dashboard")->with("toast_success", "Link Dokumen Seminar Berhasil Ditambahkan");
	}

	private function UpdateStatus(Request $request, Seminar $seminar)
	{
		$validated = $request->validate(
		[
			"status" => "required|integer|in:0,1"
		]);

		$seminar->update(
		[
			"status" => $validated["status"]
		]);

		return redirect()->route("admin.seminars")->with("toast_success", "Pengajuan Seminar Berhasil " . $request->text);
	}

	public function Accept(Request $request, Seminar $seminar)
	{
		$request->merge(
		[
			"status" => 1,
			"text" => "Diverifikasi"
		]);

		return $this->UpdateStatus($request, $seminar);
	}

	public function Comment(Request $request, Seminar $seminar)
	{
		$validated = $request->validate(
		[
			"comment" => "required|string|max:1000"
		]);

		$seminar->update(
		[
			"comment" => $validated["comment"]
		]);

		return redirect()->route("admin.seminars")->with("toast_success", "Pesan Revisi Berhasil Tersimpan");
	}

	public function Reject(Request $request, Seminar $seminar)
	{
		$request->merge(
		[
			"status" => 0,
			"text" => "Ditolak"
		]);

		return $this->UpdateStatus($request, $seminar);
	}

	public function Destroy(Seminar $seminar)
	{
		$seminar->delete();

		if ($this->userRole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Data Seminar Berhasil Dihapus");
	}
}