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
		return self::GetAll()->sortBy("name");
	}

	public function Store(Request $request)
	{
		$validated = $request->validate(
		[
			"name" => "required|string|max:255",
		]);

		Room::create(
		[
			"roomid" => (string)Str::uuid(),
			"name" => $validated["name"],
		]);

		return redirect()->route("admin.rooms")->with("toast_success", "Data Ruangan Berhasil Ditambahkan");
	}

	public function Update(Request $request, Room $room)
	{
		$validated = $request->validate(
		[
			"name" => "required|string|max:255",
		]);

		$room->update(
		[
			"name" => $validated["name"],
		]);

		return redirect()->route("admin.rooms")->with("toast_success", "Data Ruangan Berhasil Diubah");
	}

	public function Destroy(Room $room)
	{
		$room->delete();

		return redirect()->route("admin.rooms")->with("toast_success", "Ruang Berhasil Dihapus");
	}
}