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

	public static function GetAll()
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

	private function Update(Array $data, Seminar $seminar)
	{
		if (isset($data["status"]))
		{
			$seminar->update(["status" => $data["status"]]);

			if ($data["status"] === 1)
				$seminar->update(["comment" => ""]);
			
			return redirect()->route("admin.seminars")->with("toast_success", "Pengajuan Seminar Berhasil " . ($data["text"] ?? ""));
		}
		else if (isset($data["comment"]))
		{
			$seminar->update(["comment" => $data["comment"]]);

			return redirect()->route("admin.seminars")->with("toast_success", "Pesan Revisi Berhasil Tersimpan");
		}
		else if (isset($data["link"]))
		{
			$seminar->update(["link" => $data["link"]]);

			return redirect()->route("student.dashboard")->with("toast_success", "Link Dokumen Seminar Berhasil Ditambahkan");
		}
	}

	public function UpdateLink(Request $request)
	{
		$data = $request->validate(
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

		return $this->Update($data, $seminarToUpdate);
	}

	public static function GetCommentById(String $seminarid)
	{
		$seminar = self::GetAll()->filter(function($seminar) use ($seminarid)
		{
			return $seminar->seminarid === $seminarid;
		})->first();

		return $seminar ? $seminar->comment : null;
	}

	public function Accept(Request $request, Seminar $seminar)
	{
		$data = $request->merge(
		[
			"status" => 1,
			"text" => "Diverifikasi"
		])->all();

		return $this->Update($data, $seminar);
	}

	public function Comment(Request $request, Seminar $seminar)
	{
		$data = $request->validate(
		[
			"comment" => "required|string|max:1000"
		]);

		return $this->Update($data, $seminar);
	}

	public function Reject(Request $request, Seminar $seminar)
	{
		$data = $request->merge(
		[
			"status" => 0,
			"text" => "Ditolak"
		])->all();

		return $this->Update($data, $seminar);
	}

	public function Destroy(Seminar $seminar)
	{
		$seminar->delete();

		if ($this->userRole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Data Seminar Berhasil Dihapus");
	}
}