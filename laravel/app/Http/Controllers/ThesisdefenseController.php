<?php

namespace App\Http\Controllers;

use App\Models\Thesisdefense;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ThesisdefenseController extends Controller
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
		return Thesisdefense::all();
	}

	public function Index()
	{
		if ($this->userRole === "admin")
		{
			$datathesisdefense = self::GetAll();
		}
		else if ($this->userRole === "student")
		{
			$userId = $this->userId;

			$datathesisdefense = self::GetAll()->filter(function($thesisdefense) use ($userId)
			{
				return $thesisdefense->useridnumber === $userId;
			});
		}

		return $datathesisdefense;
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
		$validated["useridnumber"] = strtolower($validated["useridnumber"]);
		$validated["supervisor1"] = strtolower($validated["supervisor1"]);
		$validated["supervisor2"] = strtolower($validated["supervisor2"]);

		Thesisdefense::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter", ["type" => "thesisdefense"])->with("toast_success", "Sidang Akhir Berhasil Dibuat");
	}

	private function Update(Array $data, Thesisdefense $thesisdefense)
	{
		if (isset($data["status"]))
		{
			$thesisdefense->update(["status" => $data["status"]]);

			if ($data["status"] === 1)
				$thesisdefense->update(["comment" => ""]);
			
			return redirect()->route("admin.seminars")->with("toast_success", "Pengajuan Seminar Berhasil " . ($data["text"] ?? ""));
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

		$userId = $this->userId;

		$thesisdefenses = self::GetAll()->filter(function($thesisdefense) use ($userId)
		{
			return $thesisdefense->useridnumber === $userId;
		});

		$thesisdefenseToUpdate = $thesisdefenses->filter(function($s)
		{
			return empty($s->link);
		})->sortBy("created_at")->first();

		if (!$thesisdefenseToUpdate)
			return redirect()->back()->with("toast_info", "Semua Data Sidang Akhir Anda Sudah Lengkap");

		return $this->Update($data, $thesisdefenseToUpdate);
	}

	public static function GetCommentById(String $thesisdefenseid)
	{
		$thesisdefense = self::GetAll()->filter(function($thesisdefense) use ($thesisdefenseid)
		{
			return $thesisdefense->thesisdefenseid === $thesisdefenseid;
		})->first();

		return $thesisdefense ? $thesisdefense->comment : null;
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