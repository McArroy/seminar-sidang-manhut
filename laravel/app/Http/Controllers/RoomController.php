<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\HelperController;

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
			$query->where("roomid", "!=", $data["roomid"]);

		if ($query->exists())
			return HelperController::Message("dialog_info", [$isUpdate ? "Gagal Mengubah Data Ruangan" : "Gagal Menambahkan Data Ruangan", "Data Ruangan Sudah Pernah Ditambahkan"]);
	
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

		return HelperController::Message("toast_success", "Data Ruangan Berhasil Ditambahkan");
	}

	public function Update(Request $request, string $roomid)
	{
		$room = Room::where("roomid", $roomid)->first();

		if (!$room)
			return HelperController::Message("dialog_info", ["Gagal Mengubah Data Ruangan", "Data Ruangan Tidak Ditemukan"]);

		$validated = $this->Validate($request);

		$validated["roomid"] = trim($roomid);

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;

		$room->update($validated);

		return HelperController::Message("toast_success", "Data Ruangan Berhasil Diubah");
	}

	public function Destroy(string $roomid)
	{
		$room = Room::where("roomid", $roomid)->first();

		if (!$room)
			return HelperController::Message("dialog_info", ["Gagal Menghapus Data Ruangan", "Data Ruangan Tidak Ditemukan"]);

		$room->delete();

		return HelperController::Message("toast_success", "Ruang Berhasil Dihapus");
	}
}