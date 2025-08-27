<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
	private function Validate(Request $request) : array
	{
		$validated = $request->validate(
		[
			"roomname" => "required|string|max:255",
		]);

		return $validated;
	}

	private function CheckData(?array $data, bool $isUpdate = false)
	{
		$query = Room::where("roomname", $data["roomname"]);

		if ($isUpdate)
			$query->where("roomid", '!=', $data["roomid"]);

		if ($query->exists())
			return redirect()->back()->with("dialog_info", ["Gagal " . ($isUpdate ? "Mengubah " : "Menambahkan ") . "Data Ruangan", "Data Ruangan Sudah Pernah Ditambahkan", "Tutup", "", "", ""]);
	
		return null;
	}

	public static function GetAll(array $columns = ["*"])
	{
		return Room::select($columns)->get();
	}

	public function Index(Request $request)
	{
		return self::GetAll()->sortBy("roomname");
	}

	public function Store(Request $request)
	{
		$validated = $this->Validate($request);

		$validated["roomid"] = (string)Str::uuid();

		$check = $this->CheckData($validated);

		if ($check !== null)
			return $check;

		Room::create($validated);

		return redirect()->route("admin.rooms")->with("toast_success", "Data Ruangan Berhasil Ditambahkan");
	}

	public function Update(Request $request, string $roomid)
	{
		$room = Room::where("roomid", $roomid)->first();

		if (!$room)
			return redirect()->back()->with("dialog_info", ["Gagal Mengubah Data Ruangan", "Data Ruangan Tidak Ditemukan", "Tutup", "", "", ""]);

		$validated = $this->Validate($request);

		$check = $this->CheckData($validated);

		if ($check !== null)
			return $check;

		$room->update($validated);

		return redirect()->route("admin.rooms")->with("toast_success", "Data Ruangan Berhasil Diubah");
	}

	public function Destroy(string $roomid)
	{
		$room = Room::where("roomid", $roomid)->first();

		if (!$room)
			return redirect()->back()->with("dialog_info", ["Gagal Menghapus Data Ruangan", "Data Ruangan Tidak Ditemukan", "Tutup", "", "", ""]);

		$room->delete();

		return redirect()->route("admin.rooms")->with("toast_success", "Ruang Berhasil Dihapus");
	}
}