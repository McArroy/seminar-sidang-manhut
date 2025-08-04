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

	public function GetAll()
	{
		return Thesisdefense::all();
	}

	public function Index()
	{
		if ($this->userRole === "admin")
		{
			$datathesisdefense = $this->GetAll();
		}
		else if ($this->userRole === "student")
		{
			$userId = $this->userId;

			$datathesisdefense = $this->GetAll()->filter(function($thesisdefense) use ($userId)
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

		Thesisdefense::create($validated);

		session(["validated_data_letter" => $validated]);

		return redirect()->route("student.registrationletter", ["type" => "thesisdefense"])->with("toast_success", "Sidang Akhir Berhasil Dibuat");
	}

	public function UpdateLink(Request $request)
	{
		$validated = $request->validate(
		[
			"link" => "required|string|max:1000"
		]);

		$userId = $this->userId;

		$thesisdefenses = $this->GetAll()->filter(function($thesisdefense) use ($userId)
		{
			return $thesisdefense->useridnumber === $userId;
		});

		$thesisdefenseToUpdate = $thesisdefenses->filter(function($s)
		{
			return empty($s->link);
		})->sortBy("created_at")->first();

		if (!$thesisdefenseToUpdate)
			return redirect()->back()->with("toast_info", "Semua Data Sidang Akhir Anda Sudah Lengkap");

		$thesisdefenseToUpdate->update(
		[
			"link" => $validated["link"]
		]);

		return redirect()->route("student.dashboard")->with("toast_success", "Link Dokumen Sidang Akhir Berhasil Ditambahkan");
	}

	private function UpdateStatus(Request $request, Thesisdefense $thesisdefense)
	{
		$validated = $request->validate(
		[
			"status" => "required|integer|in:0,1"
		]);

		$thesisdefense->update(
		[
			"status" => $validated["status"]
		]);

		return redirect()->route("admin.thesisdefenses")->with("toast_success", "Pengajuan Sidang Akhir Berhasil " . $request->text);
	}

	public function Accept(Request $request, Thesisdefense $thesisdefense)
	{
		$request->merge(
		[
			"status" => 1,
			"text" => "Diverifikasi"
		]);

		return $this->UpdateStatus($request, $thesisdefense);
	}

	public function Comment(Request $request, Thesisdefense $thesisdefense)
	{
		$validated = $request->validate(
		[
			"comment" => "required|string|max:1000"
		]);

		$thesisdefense->update(
		[
			"comment" => $validated["comment"]
		]);

		return redirect()->route("admin.thesisdefenses")->with("toast_success", "Pesan Revisi Berhasil Tersimpan");
	}

	public function Reject(Request $request, Thesisdefense $thesisdefense)
	{
		$request->merge(
		[
			"status" => 0,
			"text" => "Ditolak"
		]);

		return $this->UpdateStatus($request, $thesisdefense);
	}

	public function Destroy(Thesisdefense $thesisdefense)
	{
		$thesisdefense->delete();

		if ($this->userRole === "student")
			return redirect()->route("student.dashboard")->with("toast_success", "Data Sidang Akhir Berhasil Dihapus");
	}
}