<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use App\Traits\DeterministicEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
	use DeterministicEncryption;
	
	private string $userId;
	private string $userRole;

	public function __construct()
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;
	}

	public static function GetAll(array $columns = ["*"])
	{
		return Seminar::select($columns)->get();
	}

	public function Index()
	{
		if ($this->userRole === "admin")
		{
			$dataseminar = self::GetAll(["seminarid", "useridnumber", "date", "title", "link", "status", "comment", "created_at"]);
		}
		else if ($this->userRole === "student")
		{
			$userId = $this->userId;

			$dataseminar = self::GetAll()->filter(function($seminar) use ($userId)
			{
				return $seminar->useridnumber === $userId;
			});
		}

		return $dataseminar;
	}

	public function Created(Request $request)
	{
		$data = session()->pull("validated_data_letter", []);

		if (empty($data))
			return redirect()->route("student.dashboard");

		return view("student.registrationletter", compact("data"));
	}

	public function RePreview(Request $request)
	{
		$dataSeminar = Seminar::where("seminarid", $request->id);
		$dataSeminar = 
		[
			"useridnumber" => $dataSeminar->value("useridnumber"),
			"studyprogram" => $dataSeminar->value("studyprogram"),
			"department" => $dataSeminar->value("department"),
			"supervisor1" => $dataSeminar->value("supervisor1"),
			"supervisor2" => $dataSeminar->value("supervisor2"),
			"date" => $dataSeminar->value("date"),
			"time" => $dataSeminar->value("time"),
			"place" => $dataSeminar->value("place"),
			"title" => $dataSeminar->value("title"),
			"link" => $dataSeminar->value("link"),
			"comment" => $dataSeminar->value("comment"),
			"status" => $dataSeminar->value("status"),
		];

		session(["validated_data_letter" => $dataSeminar]);

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
		$validated["useridnumber"] = strtolower(trim($this->userId));
		$validated["supervisor1"] = trim($validated["supervisor1"]);
		$validated["supervisor2"] = trim($validated["supervisor2"]);

		Seminar::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter", ["type" => "seminar"])->with("toast_success", "Seminar Berhasil Dibuat");
	}

	private function Update(array $data, Seminar $seminar)
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

		$userId = $this->encryptDeterministic($this->userId);

		$seminarToUpdate = Seminar::where("useridnumber", $userId)->whereNull("link")->orderBy("created_at")->first();

		if (!$seminarToUpdate)
			return redirect()->back()->with("toast_info", "Semua Data Seminar Anda Sudah Lengkap");

		return $this->Update($data, $seminarToUpdate);
	}

	public static function GetDataTime() : array
	{
		return
		[
			"07:00 - 08:00",
			"07:30 - 08:30",
			"08:00 - 09:00",
			"08:30 - 09:30",
			"09:00 - 10:00",
			"09:30 - 10:30",
			"10:00 - 11:00",
			"10:30 - 11:30",
			"11:00 - 12:00",
			"11:30 - 12:30",
			"12:00 - 13:00",
			"12:30 - 13:30",
			"13:00 - 14:00",
			"13:30 - 14:30",
			"14:00 - 15:00",
			"14:30 - 15:30",
			"15:00 - 16:00",
			"15:30 - 16:30",
			"16:00 - 17:00"
		];
	}

	public static function GetCommentById(string $seminarid)
	{
		$comment = Seminar::findOrFail($seminarid)->comment;

		return response()->json(["comment" => $comment]);
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
		if ($seminar->status === 1)
			return redirect()->back()->with("toast_info", "Daftar Pengajuan Seminar Yang Sudah Disetujui Tidak Bisa Dihapus");

		$seminar->delete();

		if ($this->userRole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Data Seminar Berhasil Dihapus");
	}
}