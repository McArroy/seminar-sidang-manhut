<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public static function GetAll()
    {
        return Room::orderBy("name")->get();
    }

    public function Index(Request $request)
    {
        $rooms = self::GetAll();

        $search = mb_strtolower(trim($request->query("search")));
        if ($search)
        {
            $rooms = $rooms->filter(function($room) use ($search)
            {
                return str_contains(mb_strtolower(trim($room->name ?? "")), $search);
            })->values();
        }

        return $rooms;
    }

    public function Store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255|unique:rooms,name",
        ]);

        Room::create([
            "roomid" => (string) Str::uuid(),
            "name" => trim($validated["name"]),
        ]);

        return redirect()->route("admin.rooms")->with("toast_success", "Ruang Berhasil Ditambahkan");
    }

    public function Update(Request $request, Room $room)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255|unique:rooms,name," . $room->roomid . ",roomid",
        ]);

        $room->update([
            "name" => trim($validated["name"]),
        ]);

        return redirect()->route("admin.rooms")->with("toast_success", "Ruang Berhasil Diubah");
    }

    public function Destroy(Room $room)
    {
        $room->delete();
        return redirect()->route("admin.rooms")->with("toast_success", "Ruang Berhasil Dihapus");
    }
}


