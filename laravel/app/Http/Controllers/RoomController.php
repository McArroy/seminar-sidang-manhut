<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\HelperController;

class RoomController extends Controller
{
	private function Validate(Request $request, bool $isUpdate = false) : array
	{
		$validated = $request->validate(
		[
			"roomname" => "required|string|max:255",
		]);

		if (!$isUpdate)
			$validated["roomid"] = (string)Str::uuid();

		return $validated;
	}

	private function CheckData(?array $data, bool $isUpdate = false)
	{
		$query = Room::where("roomname", $data["roomname"]);

		if ($isUpdate)
			$query->where("roomid", "!=", $data["roomid"]);

		if ($query->exists())
			return HelperController::Message("dialog_info", [$isUpdate ? __("room.failedtochange") : __("room.failedtocreate"), __("room.existed")]);
	
		return null;
	}

	public static function GetAll(array $columns = ["*"])
	{
		return Room::select($columns)->get();
	}

	public function Index(Request $request)
	{
		return self::GetAll()->sortBy(function($item)
		{
			return strtolower($item->roomname);
		})->values();
	}

	public function Store(Request $request)
	{
		$validated = $this->Validate($request);

		$check = $this->CheckData($validated);

		if ($check !== null)
			return $check;

		Room::create($validated);

		return HelperController::Message("toast_success", __("room.succeededtocreate"));
	}

	public function Update(Request $request, string $roomid)
	{
		$room = Room::where("roomid", $roomid)->first();

		if (!$room)
			return HelperController::Message("dialog_info", [__("room.failedtochange"), __("room.notfound")]);

		$validated = $this->Validate($request, true);

		$validated["roomid"] = trim($roomid);

		$check = $this->CheckData($validated, true);

		if ($check !== null)
			return $check;

		$room->update($validated);

		return HelperController::Message("toast_success", __("room.succeededtochange"));
	}

	public function Destroy(string $roomid)
	{
		$room = Room::where("roomid", $roomid)->first();

		if (!$room)
			return HelperController::Message("dialog_info", [__("room.failedtodelete"), __("room.notfound")]);

		$room->delete();

		return HelperController::Message("toast_success", __("room.succeededtodelete"));
	}
}