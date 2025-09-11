<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\DeterministicEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\HelperController;

class UserController extends Controller
{
	use DeterministicEncryption;

	private string $userId;
	private string $userRole;
	private string $queryRole;

	public function __construct(Request $request)
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;

		$allowedRoles = ["admin", "student", "lecturer"];
		$role = $request->query("role", "admin");
		$this->queryRole = in_array($role, $allowedRoles) ? $role : "admin";
	}

	private function Validate(Request $request, bool $isUpdate = false) : array
	{
		$request->merge(
		[
			"is_active" => (int)$request->input("is_active", 1)
		]);

		$rules =
		[
			"username" => "required|string|max:255",
			"password" =>
			[
				$isUpdate ? "nullable" : "required",
				"string",
				"max:127"
			],
			"is_active" => "required|integer|in:0,1"
		];

		if (!$isUpdate)
			$rules["useridnumber"] = "required|string|max:33";

		$validated = $request->validate($rules);

		if (!$isUpdate)
		{
			$validated["userid"] = (string)Str::uuid();
			$validated["useridnumber"] = strtolower(trim($validated["useridnumber"]));
			$validated["userrole"] = $request["userrole"];
			$validated["password"] = Hash::make($validated["password"]);
		}
		else
		{
			if (!empty($validated["password"]))
				$validated["password"] = Hash::make($validated["password"]);
			else
				unset($validated["password"]);
		}

		return $validated;
	}

	private function CheckData(?array $data, bool $isUpdate = false)
	{
		$query = User::where("useridnumber", $isUpdate ? $data["useridnumber"] : $this->encryptDeterministic($data["useridnumber"]));

		if ($isUpdate)
			$query->where("userid", "!=", $data["userid"]);

		if ($query->exists())
			return HelperController::Message("toast_info", __("user." . $this->queryRole . ".existeduseridnumber") . ". " . ($isUpdate
					? __("user." . $this->queryRole . ".failedtochange")
					: __("user." . $this->queryRole . ".failedtocreate")));

		if (($data["useridnumber"] === $this->userId) && $data["is_active"] === 0)
			return HelperController::Message("dialog_info", [__("user.admin.failedtochange"), "Anda Tidak Bisa Menonaktifkan Data Diri Anda"]);
	
		return null;
	}

	public static function GetAll(array $columns = ["*"])
	{
		return User::select($columns)->get();
	}

	public static function GetUsername(?string $useridnumber)
	{
		if (empty($useridnumber))
			return null;

		return User::where("useridnumber", DeterministicEncryption::encryptDeterministic(strtolower(trim($useridnumber))))->value("username");
	}

	public function GetUsers(string $userrole = "admin", bool $onlyIsActive = false)
	{
		return self::GetAll(["userid", "useridnumber", "username", "userrole", "is_active", "created_at"])->filter(function($user) use ($userrole, $onlyIsActive)
		{
			return ($user->userrole === $userrole && (!$onlyIsActive || $user->is_active == 1));
		});
	}

	public function Store(Request $request)
	{
		$request->merge(
		[
			"userrole" => $this->queryRole
		]);

		$validated = $this->Validate($request);

		$check = $this->CheckData($validated, false);

		if ($check !== null)
			return $check;

		User::create($validated);

		return HelperController::Message("toast_success", __("user." . $this->queryRole . ".succeededtocreate"));
	}

	public function Update(Request $request, string $userid)
	{
		$user = User::where("userid", $userid)->first();

		if (!$user)
			return HelperController::Message("dialog_info", [__("user." . $this->queryRole . ".failedtochange"), __("user." . $this->queryRole . ".notfound")]);

		$validated = $this->Validate($request, true);

		$validated["userid"] = trim($userid);
		$validated["useridnumber"] = trim($user->useridnumber);

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;

		$user->update($validated);

		return HelperController::Message("toast_success", __("user." . $this->queryRole . ".succeededtochange"));
	}

	public function Destroy(Request $request, string $userid)
	{
		$user = User::where("userid", $userid)->first();

		if (!$user)
			return HelperController::Message("dialog_info", [__("user." . $this->queryRole . ".failedtodelete"), __("user." . $this->queryRole . ".notfound")]);

		if ($userid === Auth::user()->userid)
			return HelperController::Message("dialog_info", ["Gagal Menghapus Data Admin", "Anda Tidak Bisa Menghapus Data Diri Anda"]);

		$user->delete();

		return HelperController::Message("toast_success", __("user." . $this->queryRole . ".succeededtodelete"));
	}
}