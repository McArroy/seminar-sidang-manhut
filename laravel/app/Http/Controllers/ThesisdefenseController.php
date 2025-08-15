<?php

namespace App\Http\Controllers;

use App\Models\Thesisdefense;
use App\Traits\DeterministicEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ThesisdefenseController extends Controller
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
		return Thesisdefense::select($columns)->get();
	}

	public function Index()
	{
		if ($this->userRole === "admin")
		{
			$dataThesisdefense = self::GetAll(["thesisdefenseid", "useridnumber", "date", "title", "link", "status", "comment", "created_at"]);
		}
		else if ($this->userRole === "student")
		{
			$userId = $this->userId;

			$dataThesisdefense = self::GetAll()->filter(function($thesisdefense) use ($userId)
			{
				return $thesisdefense->useridnumber === $userId;
			});
		}

		return $dataThesisdefense;
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
		$dataThesisdefense = Thesisdefense::where("thesisdefenseid", $request->id);
		$dataThesisdefense = 
		[
			"useridnumber" => $dataThesisdefense->value("useridnumber"),
			"semester" => $dataThesisdefense->value("semester"),
			"address" => $dataThesisdefense->value("address"),
			"supervisor1" => $dataThesisdefense->value("supervisor1"),
			"supervisor2" => $dataThesisdefense->value("supervisor2"),
			"date" => $dataThesisdefense->value("date"),
			"time" => $dataThesisdefense->value("time"),
			"place" => $dataThesisdefense->value("place"),
			"title" => $dataThesisdefense->value("title"),
			"link" => $dataThesisdefense->value("link"),
			"comment" => $dataThesisdefense->value("comment"),
			"status" => $dataThesisdefense->value("status"),
		];

		session(["validated_data_letter" => $dataThesisdefense]);

		return redirect()->route("student.registrationletter", ["type" => "thesisdefense"]);
	}

	public function Store(Request $request)
	{
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
		$validated["useridnumber"] = strtolower(trim($this->userId));
		$validated["supervisor1"] = strtolower(trim($validated["supervisor1"]));
		$validated["supervisor2"] = strtolower(trim($validated["supervisor2"]));

		Thesisdefense::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter", ["type" => "thesisdefense"])->with("toast_success", "Sidang Akhir Berhasil Dibuat");
	}

	private function Update(array $data, Thesisdefense $thesisdefense)
	{
		if (isset($data["status"]))
		{
			$thesisdefense->update(["status" => $data["status"]]);

			if ($data["status"] === 1)
				$thesisdefense->update(["comment" => ""]);
			
			return redirect()->route("admin.thesisdefenses")->with("toast_success", "Pengajuan Seminar Berhasil " . ($data["text"] ?? ""));
		}
		else if (isset($data["comment"]))
		{
			$thesisdefense->update(["comment" => $data["comment"]]);

			return redirect()->route("admin.thesisdefenses")->with("toast_success", "Pesan Revisi Berhasil Tersimpan");
		}
		else if (isset($data["link"]))
		{
			$thesisdefense->update(["link" => $data["link"]]);

			return redirect()->route("student.dashboard")->with("toast_success", "Link Dokumen Sidang Akhir Berhasil Ditambahkan");
		}
	}

	public function UpdateLink(Request $request)
	{
		$data = $request->validate(
		[
			"link" => "required|string|max:1000"
		]);

		$userId = $this->encryptDeterministic($this->userId);

		$thesisdefenseToUpdate = Thesisdefense::where("useridnumber", $userId)->whereNull("link")->orderBy("created_at")->first();

		if (!$thesisdefenseToUpdate)
			return redirect()->back()->with("toast_info", "Semua Data Sidang Akhir Anda Sudah Lengkap");

		return $this->Update($data, $thesisdefenseToUpdate);
	}

	public static function GetDataTime() : array
	{
		return
		[
			"07:00 - 09:00",
			"07:30 - 09:30",
			"08:00 - 10:00",
			"08:30 - 10:30",
			"09:00 - 11:00",
			"09:30 - 11:30",
			"10:00 - 12:00",
			"10:30 - 12:30",
			"11:00 - 13:00",
			"11:30 - 13:30",
			"12:00 - 14:00",
			"12:30 - 14:30",
			"13:00 - 15:00",
			"13:30 - 15:30",
			"14:00 - 16:00",
			"14:30 - 16:30",
			"15:00 - 17:00",
			"15:30 - 17:30",
			"16:00 - 18:00"
		];
	}

	public static function GetCommentById(string $thesisdefenseid)
	{
		$comment = Thesisdefense::findOrFail($thesisdefenseid)->comment;

		return response()->json(["comment" => $comment]);
	}

	public function Accept(Request $request, Thesisdefense $thesisdefense)
	{
		$data = $request->merge(
		[
			"status" => 1,
			"text" => "Diverifikasi"
		])->all();

		return $this->Update($data, $thesisdefense);
	}

	public function Comment(Request $request, Thesisdefense $thesisdefense)
	{
		$data = $request->validate(
		[
			"comment" => "required|string|max:1000"
		]);

		return $this->Update($data, $thesisdefense);
	}

	public function Reject(Request $request, Thesisdefense $thesisdefense)
	{
		$data = $request->merge(
		[
			"status" => 0,
			"text" => "Ditolak"
		])->all();

		return $this->Update($data, $thesisdefense);
	}

	public function Destroy(Thesisdefense $thesisdefense)
	{
		$thesisdefense->delete();

		if ($this->userRole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Data Sidang Akhir Berhasil Dihapus");
	}
}