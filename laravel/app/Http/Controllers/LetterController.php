<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\Academic;
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
		$this->queryType = $request->query("type", "seminar");
		$this->isThesis = $this->queryType === "thesisdefense";
	}

	private function Validate(Request $request, bool $isUpdate = false, ?string $academicid = "") : array
	{
		$validated = $request->validate(
		[
			"letternumber" => "required|string|max:33",
			"letterdate" => "required|date",
			"moderator" =>
			[
				$this->isThesis ? "nullable" : "required",
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

		if (!$isUpdate)
			$validated["letterid"] = $academicid;

		return $validated;
	}

	private function CheckData(?array $data, bool $isUpdate = false)
	{
		if (self::IsExist($data["letterid"], $this->queryType) && !$isUpdate)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtocreateletter"), __($this->queryType . ".existedletter")]);

		$query = Letter::where("letternumber", $this->encryptDeterministic($data["letternumber"]));

		if ($isUpdate)
			$query->where("letterid", "!=", $data["letterid"]);

		if ($query->exists())
			return HelperController::Message("dialog_info", [$isUpdate ? __($this->queryType . ".failedtochangeletter") : __($this->queryType . ".failedtocreateletter"), __($this->queryType . ".existedletternumber")]);
	
		return null;
	}

	public static function GetAll(array $columns = ["*"])
	{
		return Letter::select($columns)->get();
	}

	public static function IsExist(?string $academicid) : bool
	{
		if (empty($academicid))
			return false;

		$query = Letter::where("letterid", $academicid);

		return $query->exists();
	}

	public static function GetValuesByAcademicId(?string $academicid)
	{
		if (empty($academicid))
			return response()->json([]);

		$letter = Letter::where("letterid", $academicid)->first();
		
		if (!$letter)
			return response()->json([]);

		$data =
		[
			"letterid" => $letter->letterid,
			"letternumber" => $letter->letternumber,
			"moderator" => $letter->moderator,
			"letterdate" => $letter->letterdate,
			"external_examiner" => $letter->external_examiner,
			"chairman_session" => $letter->chairman_session
		];

		return response()->json($data);
	}

	public function Store(Request $request, string $academicid)
	{
		$validated = $this->Validate($request, false, $academicid);

		$check = $this->CheckData($validated);

		if ($check !== null)
			return $check;
		
		Letter::create($validated);

		Academic::where("academicid", $academicid)->first()->update(["is_completed" => 1]);

		return HelperController::Message("toast_success", __($this->queryType . ".succeededtocreateletter"));
	}

	public function Update(Request $request, string $academicid)
	{
		$letter = Letter::where("letterid", $academicid)->first();

		if (!$letter)
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtochangeletter"), __($this->queryType . ".letternotfound")]);
		
		$validated = $this->Validate($request, true);

		$validated["letterid"] = $academicid;

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;

		$letter->update($validated);

		return HelperController::Message("toast_success", __($this->queryType . ".succeededtochangeletter"));
	}

	public function Print(?string $academicid)
	{
		if (empty($academicid))
			return redirect()->back();

		if (!self::IsExist($academicid))
			return HelperController::Message("dialog_info", [__($this->queryType . ".failedtoprintletter"), __($this->queryType . ".letterformnotfound")]);
	}
}