<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Traits\DeterministicEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller
{
	use DeterministicEncryption;

	private string $userId;
	private string $userRole;
	private string $queryType;

	public function __construct(Request $request)
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;
		$this->queryType = $request->query("type", "");
	}

	private function Validate(Request $request) : array
	{
		$isThesis = $this->queryType === "thesisdefense";
		
		$validated = $request->validate(
		[
			"letternumber" => "required|string|max:33",
			"moderator" => "required|string|max:255",
			"letterdate" => "required|date",
			"supervisory_committee" =>
			[
				$isThesis ? "required" : "nullable",
				"string",
				"max:255"
			],
			"external_examiner" =>
			[
				$isThesis ? "required" : "nullable",
				"string",
				"max:255"
			],
			"chairman_session" =>
			[
				$isThesis ? "required" : "nullable",
				"string",
				"max:255"
			]
		]);

		return $validated;
	}

	private function CheckData(?array $data, bool $isUpdate = false)
	{
		$isThesis = $this->queryType === "thesisdefense";

		if (self::IsExist($data["academicid"], $this->queryType) && !$isUpdate)
			return redirect()->back()->with("dialog_info", ["Gagal Membuat " . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar"), ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar") . " Sudah Pernah Dibuat", "Tutup", "", "", ""]);

		if (Letter::where("letternumber", $this->encryptDeterministic($data["letternumber"]))->exists())
			return redirect()->back()->with("dialog_info", ["Gagal " . ($isUpdate ? "Memperbarui " : "Membuat ") . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar"), "Nomor Surat Pada " . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar") . " Sudah Pernah Dibuat (Tidak Boleh Sama)", "Tutup", "", "", ""]);
	
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
		$isThesis = $this->queryType === "thesisdefense";

		$validated = $this->Validate($request);

		$validated["letterid"] = (string)Str::uuid();
		$validated["academicid"] = trim($academicid);

		$check = $this->CheckData($validated);

		if ($check !== null)
			return $check;
		
		Letter::create($validated);

		return redirect()->route("admin.announcements", ["type" => $this->queryType])->with("toast_success", ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar") . " Berhasil Dibuat");
	}

	public function Update(Request $request, string $academicid)
	{
		$isThesis = $this->queryType === "thesisdefense";

		$validated = $this->Validate($request);

		$validated["academicid"] = trim($academicid);

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;
		
		$letter = Letter::where("academicid", $academicid)->first();

		if (!$letter)
			return redirect()->back()->with("dialog_info", ["Gagal Memperbarui " . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar"), "Data " . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar") . " Tidak Ditemukan", "Tutup", "", "", ""]);

		$letter->update($validated);

		return redirect()->route("admin.announcements", ["type" => $this->queryType])->with("toast_success", ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar") . " Berhasil Diperbarui");
	}

	public function Print(?string $academicid)
	{
		$isThesis = $this->queryType === "thesisdefense";

		if (empty($academicid))
			return redirect()->back();

		if (!self::IsExist($academicid))
			return redirect()->back()->with("dialog_info", ["Gagal Mencetak " . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar"), "Form " . ($isThesis ? "Undangan Sidang Akhir" : "Pengumuman Seminar") . " Harus Dibuat Terlebih Dahulu", "Tutup", "", "", ""]);
	}
}