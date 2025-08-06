<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	private string $userRole;

	public function __construct()
	{
		$this->userRole = Auth::user()->userrole;
	}

	private function Store(Request $request)
	{
		$validated = $request->validate(
		[
			"useridnumber" => "required|string|max:33",
			"username" => "required|string|max:255",
			"password" => "required|string|max:127"
		]);

		$exists = self::GetAll()->contains(function($u) use ($validated)
			{
				return $u->useridnumber === $validated["useridnumber"];
			});

		if ($exists)
			return redirect()->route("admin." . $request["from"])->with("toast_info", "NIM/NIP Sudah Digunakan. Gagal Menambahkan Data " . $request["text"]);

		$validated["userid"] = (string)Str::uuid();
		$validated["useridnumber"] = strtolower($validated["useridnumber"]);
		$validated["userrole"] = $request["userrole"];
		$validated["password"] = Hash::make($validated["password"]);

		User::create($validated);

		return redirect()->route("admin." . $request["from"])->with("toast_success", "Data " . $request["text"] . " Berhasil Dibuat");
	}

	private function Update(Request $request, User $user)
	{
		$validated = $request->validate(
		[
			"useridnumber" => "required|string|max:33",
			"username" => "required|string|max:255",
			"password" => "nullable|string|max:127"
		]);

		$validated["useridnumber"] = strtolower($validated["useridnumber"]);

		$exists = $this->GetAll()->filter(function($u) use ($validated, $user)
			{
				return $u->useridnumber === $validated["useridnumber"] && $u->userid !== $user->userid;
			})->isNotEmpty();

		if ($exists)
			return redirect()->route("admin." . $request["from"])->with("toast_info", "NIM/NIP Sudah Digunakan. Gagal Mengubah Data " . $request["text"]);

		$user->update(
		[
			"useridnumber" => $validated["useridnumber"],
			"username" => $validated["username"]
		]);

		if (!empty($validated["password"]))
			$user->update(["password" => Hash::make($validated["password"])]);

		return redirect()->route("admin." . $request["from"])->with("toast_success", "Data " . $request["text"] . " Berhasil Diubah");
	}

	private function Destroy(Request $request, User $user)
	{
		$user->delete();

		if ($this->userRole === "admin")
			return redirect()->route("admin." . $request["from"])->with("toast_success", "Data " . $request["text"] . " Berhasil Dihapus");
	}

	public static function GetAll()
	{
		return User::all();
	}

	public static function GetUsername(String $useridnumber)
	{
		$user = self::GetAll()->filter(function($user) use ($useridnumber)
		{
			return $user->useridnumber === $useridnumber;
		})->first();

		return $user ? $user->username : null;
	}

	public static function GetUsernameById(String $userid)
	{
		$user = self::GetAll()->filter(function($user) use ($userid)
		{
			return $user->userid === $userid;
		})->first();

		return $user ? $user->username : null;
	}

	public static function GetUseridnumberById(String $userid)
	{
		$user = self::GetAll()->filter(function($user) use ($userid)
		{
			return $user->userid === $userid;
		})->first();

		return $user ? $user->useridnumber : null;
	}

	public function GetStudents()
	{
		return self::GetAll()->filter(function($user)
		{
			return $user->userrole === "student";
		});
	}

	public function StoreStudents(Request $request)
	{
		$request->merge(
		[
			"from" => "students",
			"userrole" => "student",
			"text" => "Mahasiswa"
		]);

		return $this->Store($request);
	}

	public function UpdateStudents(Request $request, User $user)
	{
		$request->merge(
		[
			"from" => "students",
			"userrole" => "student",
			"text" => "Mahasiswa"
		]);

		return $this->Update($request, $user);
	}

	public function DestroyStudents(Request $request, User $user)
	{
		$request->merge(
		[
			"from" => "students",
			"userrole" => "student",
			"text" => "Mahasiswa"
		]);

		return $this->Destroy($request, $user);
	}

	public function GetLecturers()
	{
		return $this->GetAll()->filter(function($user)
		{
			return $user->userrole === "lecturer";
		});
	}

	public function StoreLecturers(Request $request)
	{
		$request->merge(
		[
			"from" => "lecturers",
			"userrole" => "lecturer",
			"text" => "Dosen"
		]);
		
		return $this->Store($request);
	}

	public function UpdateLecturers(Request $request, User $user)
	{
		$request->merge(
		[
			"from" => "lecturers",
			"userrole" => "lecturer",
			"text" => "Dosen"
		]);
		
		return $this->Update($request, $user);
	}

	public function DestroyLecturers(Request $request, User $user)
	{
		$request->merge(
		[
			"from" => "lecturers",
			"userrole" => "lecturer",
			"text" => "Dosen"
		]);
		
		return $this->Destroy($request, $user);
	}
}