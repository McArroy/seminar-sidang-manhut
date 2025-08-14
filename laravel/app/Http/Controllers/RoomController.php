<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
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
		$validated = $request->validate(
		[
			"roomname" => "required|string|max:255",
		]);

		Room::create(
		[
			"roomid" => (string)Str::uuid(),
			"roomname" => $validated["roomname"],
		]);

		return redirect()->route("admin.rooms")->with("toast_success", "Data Ruangan Berhasil Ditambahkan");
	}

	public function Update(Request $request, Room $room)
	{
		$validated = $request->validate(
		[
			"roomname" => "required|string|max:255",
		]);

		$room->update(
		[
			"roomname" => $validated["roomname"],
		]);

		return redirect()->route("admin.rooms")->with("toast_success", "Data Ruangan Berhasil Diubah");
	}

	public static function GetRoomname(?string $roomid)
	{
		if (empty($roomid))
			return null;

		return Room::where("roomid", $roomid)->value("roomname");
	}

	public function Destroy(Room $room)
	{
		$room->delete();

		return redirect()->route("admin.rooms")->with("toast_success", "Ruang Berhasil Dihapus");
	}
}