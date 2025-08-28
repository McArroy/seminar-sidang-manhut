<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Traits\DeterministicEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HelperController;

class LetterController extends Controller
{
	use DeterministicEncryption;

	private string $userId;
	private string $userRole;
	private string $queryType;
	private bool $isThesis;

	public function __construct(Request $request)
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;
		$this->queryType = $request->query("type", "");
		$this->isThesis = $this->queryType === "thesisdefense";
	}

	private function Validate(Request $request) : array
	{
		$validated = $request->validate(
		[
			"letternumber" => "required|string|max:33",
			"moderator" => "required|string|max:255",
			"letterdate" => "required|date",
			"supervisory_committee" =>
			[
				$this->isThesis ? "required" : "nullable",
				"string",
				"max:255"
			],
			"external_examiner" =>
			[
				$this->isThesis ? "required" : "nullable",
				"string",
				"max:255"
			],
			"chairman_session" =>
			[
				$this->isThesis ? "required" : "nullable",
				"string",
				"max:255"
			]
		]);

		return $validated;
	}

	private function CheckData(?array $data, bool $isUpdate = false)
	{
		if (self::IsExist($data["academicid"], $this->queryType) && !$isUpdate)
		{
			$messages = match($this->queryType)
			{
				"seminar" => ["Gagal Membuat Pengumuman Seminar", "Pengumuman Seminar Sudah Pernah Dibuat"],
				"thesisdefense" => ["Gagal Membuat Undangan Sidang Akhir", "Undangan Sidang Akhir Sudah Pernah Dibuat"]
			};

			return HelperController::Message("dialog_info", $messages);
		}

		$query = Letter::where("letternumber", $this->encryptDeterministic($data["letternumber"]));

		if ($isUpdate)
			$query->where("academicid", '!=', $data["academicid"]);

		if ($query->exists())
		{
			$messages = match($this->queryType)
			{
				"seminar" => $isUpdate ? ["Gagal Mengubah Pengumuman Seminar", "Nomor Surat Pada Pengumuman Seminar Sudah Pernah Dibuat (Tidak Boleh Sama)"] : ["Gagal Membuat Pengumuman Seminar", "Nomor Surat Pada Pengumuman Seminar Sudah Pernah Dibuat (Tidak Boleh Sama)"],
				"thesisdefense" => $isUpdate ? ["Gagal Mengubah Undangan Sidang Akhir", "Nomor Surat Pada Undangan Sidang Akhir Sudah Pernah Dibuat (Tidak Boleh Sama)"] : ["Gagal Membuat Undangan Sidang Akhir", "Nomor Surat Pada Undangan Sidang Akhir Sudah Pernah Dibuat (Tidak Boleh Sama)"]
			};

			return HelperController::Message("dialog_info", $messages);
		}
	
		return null;
	}

	public static function GetAll(array $columns = ["*"])
	{
		return Letter::select($columns)->get();
	}

	public static function IsExist(?string $academicid, $filter = "") : bool
	{
		if (empty($academicid))
			return false;

		$query = Letter::where("academicid", $academicid);

		if (strtolower($filter) === "seminar")
			$query->where(function($query)
			{
				$query->whereNull("chairman_session")
					->orWhere("chairman_session", "");
			});

		return $query->exists();
	}

	public static function GetValuesByAcademicId(?string $academicid)
	{
		if (empty($academicid))
			return response()->json([]);

		$letter = Letter::where("academicid", $academicid)->first();
		
		if (!$letter)
			return response()->json([]);

		$data =
		[
			"letterid" => $letter->letterid,
			"academicid" => $letter->academicid,
			"letternumber" => $letter->letternumber,
			"moderator" => $letter->moderator,
			"letterdate" => $letter->letterdate,
			"supervisory_committee" => $letter->supervisory_committee,
			"external_examiner" => $letter->external_examiner,
			"chairman_session" => $letter->chairman_session
		];

		return response()->json($data);
	}

	public function Store(Request $request, string $academicid)
	{
		$validated = $this->Validate($request);

		$validated["letterid"] = (string)Str::uuid();
		$validated["academicid"] = trim($academicid);

		$check = $this->CheckData($validated);

		if ($check !== null)
			return $check;
		
		Letter::create($validated);

		$message = match($this->queryType)
		{
			"seminar" => "Pengumuman Seminar Berhasil Dibuat",
			"thesisdefense" => "Undangan Sidang Akhir Berhasil Dibuat"
		};

		return HelperController::Message("toast_success", $message);
	}

	public function Update(Request $request, string $academicid)
	{
		$validated = $this->Validate($request);

		$validated["academicid"] = trim($academicid);

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;
		
		$letter = Letter::where("academicid", $academicid)->first();

		if (!$letter)
		{
			$messages = match($this->queryType)
			{
				"seminar" => ["Gagal Mengubah Pengumuman Seminar", "Data Pengumuman Seminar Tidak Ditemukan"],
				"thesisdefense" => ["Gagal Mengubah Undangan Sidang Akhir", "Data Undangan Sidang Akhir Tidak Ditemukan"]
			};

			return HelperController::Message("dialog_info", $messages);
		}

		$letter->update($validated);

		$message = match($this->queryType)
		{
			"seminar" => "Pengumuman Seminar Berhasil Diubah",
			"thesisdefense" => "Undangan Sidang Akhir Berhasil Diubah"
		};

		return HelperController::Message("toast_success", $message);
	}

	public function Print(?string $academicid)
	{
		if (empty($academicid))
			return redirect()->back();

		if (!self::IsExist($academicid))
		{
			$messages = match($this->queryType)
			{
				"seminar" => ["Gagal Mencetak Pengumuman Seminar", "Form Pengumuman Seminar Tidak Ditemukan Atau Harus Dibuat Terlebih Dahulu"],
				"thesisdefense" => ["Gagal Mencetak Undangan Sidang Akhir", "Form Undangan Sidang Akhir Tidak Ditemukan Atau Harus Dibuat Terlebih Dahulu"]
			};

			return HelperController::Message("dialog_info", $messages);
		}
	}
}