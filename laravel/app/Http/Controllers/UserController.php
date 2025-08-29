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
	private string $queryFrom;

	public function __construct()
	{
		$this->userId = Auth::user()->useridnumber;
		$this->userRole = Auth::user()->userrole;
	}

	private function Validate(Request $request, bool $isUpdate = false) : array
	{
		$rules =
		[
			"username" => "required|string|max:255",
			"password" =>
			[
				$isUpdate ? "nullable" : "required",
				"string",
				"max:127"
			]
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
			$query->where("userid", '!=', $data["userid"]);

		if ($query->exists())
		{
			$message = match($this->queryFrom)
			{
				"admins" => $isUpdate ? "NIP Sudah Digunakan. Gagal Mengubah Data Admin" : "NIP Sudah Digunakan. Gagal Menambahkan Data Admin",
				"students" => $isUpdate ? "NIM Sudah Digunakan. Gagal Mengubah Data Mahasiswa" : "NIM Sudah Digunakan. Gagal Menambahkan Data Mahasiswa",
				"lecturers" => $isUpdate ? "NIP Sudah Digunakan. Gagal Mengubah Data Dosen" : "NIP Sudah Digunakan. Gagal Menambahkan Data Dosen"
			};
			
			return HelperController::Message("toast_info", $message);
		}
	
		return null;
	}

	private function Store(Request $request)
	{
		$this->queryFrom = $request["from"];

		$validated = $this->Validate($request);

		$check = $this->CheckData($validated, false);

		if ($check !== null)
			return $check;

		User::create($validated);

		$message = match($this->queryFrom)
		{
			"admins" => "Data Admin Berhasil Ditambahkan",
			"students" => "Data Mahasiswa Berhasil Ditambahkan",
			"lecturers" => "Data Dosen Berhasil Ditambahkan"
		};

		return HelperController::Message("toast_success", $message);
	}

	private function Update(Request $request, string $userid)
	{
		$this->queryFrom = $request["from"];

		$user = User::where("userid", $userid)->first();

		if (!$user)
		{
			$messages = match($this->queryFrom)
			{
				"admins" => ["Gagal Mengubah Data Admin", "Data Admin Tidak Ditemukan"],
				"students" => ["Gagal Mengubah Data Mahasiswa", "Data Mahasiswa Tidak Ditemukan"],
				"lecturers" => ["Gagal Mengubah Data Dosen", "Data Dosen Tidak Ditemukan"]
			};
			
			return HelperController::Message("dialog_info", $messages);
		}

		$validated = $this->Validate($request, true);

		$validated["userid"] = trim($userid);
		$validated["useridnumber"] = trim($user->useridnumber);

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;

		$user->update($validated);

		$message = match($this->queryFrom)
		{
			"admins" => "Data Admin Berhasil Diubah",
			"students" => "Data Mahasiswa Berhasil Diubah",
			"lecturers" => "Data Dosen Berhasil Diubah"
		};

		return HelperController::Message("toast_success", $message);
	}

	private function Destroy(Request $request, string $userid)
	{
		$this->queryFrom = $request["from"];

		$user = User::where("userid", $userid)->first();

		if (!$user)
		{
			$messages = match($this->queryFrom)
			{
				"admins" => ["Gagal Menghapus Data Admin", "Data Admin Tidak Ditemukan"],
				"students" => ["Gagal Menghapus Data Mahasiswa", "Data Mahasiswa Tidak Ditemukan"],
				"lecturers" => ["Gagal Menghapus Data Dosen", "Data Dosen Tidak Ditemukan"]
			};
			
			return HelperController::Message("dialog_info", $messages);
		}

		if ($userid === Auth::user()->userid)
			return HelperController::Message("dialog_info", ["Gagal Menghapus Data Admin", "Anda Tidak Bisa Menghapus Data Diri Anda"]);

		$user->delete();

		$message = match($this->queryFrom)
		{
			"admins" => "Data Admin Berhasil Dihapus",
			"students" => "Data Mahasiswa Berhasil Dihapus",
			"lecturers" => "Data Dosen Berhasil Dihapus"
		};

		return HelperController::Message("toast_success", $message);
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

	public function GetAdmins()
	{
		return self::GetAll(["userid", "useridnumber", "username", "userrole", "created_at"])->filter(function($user)
		{
			return $user->userrole === "admin";
		});
	}

	public function StoreAdmins(Request $request)
	{
		$request->merge(
		[
			"from" => "admins",
			"userrole" => "admin"
		]);

		return $this->Store($request);
	}

	public function UpdateAdmins(Request $request, string $userid)
	{
		$request->merge(
		[
			"from" => "admins"
		]);

		return $this->Update($request, $userid);
	}

	public function DestroyAdmins(Request $request, string $userid)
	{
		$request->merge(
		[
			"from" => "admins"
		]);

		return $this->Destroy($request, $userid);
	}

	public function GetStudents()
	{
		return self::GetAll(["userid", "useridnumber", "username", "userrole", "created_at"])->filter(function($user)
		{
			return $user->userrole === "student";
		});
	}

	public function StoreStudents(Request $request)
	{
		$request->merge(
		[
			"from" => "students",
			"userrole" => "student"
		]);

		return $this->Store($request);
	}

	public function UpdateStudents(Request $request, string $userid)
	{
		$request->merge(
		[
			"from" => "students"
		]);

		return $this->Update($request, $userid);
	}

	public function DestroyStudents(Request $request, string $userid)
	{
		$request->merge(
		[
			"from" => "students"
		]);

		return $this->Destroy($request, $userid);
	}

	public function GetLecturers()
	{
		return self::GetAll(["userid", "useridnumber", "username", "userrole", "created_at"])->filter(function($user)
		{
			return $user->userrole === "lecturer";
		});
	}

	public function StoreLecturers(Request $request)
	{
		$request->merge(
		[
			"from" => "lecturers",
			"userrole" => "lecturer"
		]);
		
		return $this->Store($request);
	}

	public function UpdateLecturers(Request $request, string $userid)
	{
		$request->merge(
		[
			"from" => "lecturers"
		]);
		
		return $this->Update($request, $userid);
	}

	public function DestroyLecturers(Request $request, string $userid)
	{
		$request->merge(
		[
			"from" => "lecturers"
		]);
		
		return $this->Destroy($request, $userid);
	}
}