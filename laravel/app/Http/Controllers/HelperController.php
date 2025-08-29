<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

class HelperController extends Controller
{
	public static function Asset(?string $path)
	{
		$fullPath = public_path($path);
		$hash = file_exists($fullPath) ? filemtime($fullPath) : time();

		return asset($path) . "?v=" . $hash;
	}

	public static function FilterByDateRange($data)
	{
		$today = Carbon::today();

		return $data->filter(function($item) use ($today)
		{
			if (empty($item->date))
				return false;

			$itemDate = Carbon::parse($item->date);

			if ($today->lessThanOrEqualTo($itemDate))
				return true;

			return $today->lessThanOrEqualTo($itemDate->copy()->addDay());
		})->values();
	}

	public static function MarkIfDatePassed($data)
	{
		$today = Carbon::today();

		return $data->map(function($item) use ($today)
		{
			if (!empty($item->date))
			{
				$itemDate = Carbon::parse($item->date);

				if ($today->greaterThan($itemDate->copy()->addDay()))
					$item->status_schedule = 1;
				else
					$item->status_schedule = 0;
			}
			else
			{
				$item->status_schedule = 0;
			}

			return $item;
		});
	}

	public static function Message(string $type = "dialog_info", string|array $messages)
	{
		if ($type === "dialog_info")
			$messages = array_merge($messages, ["Tutup", "", "", ""]);

		return redirect()->back()->with($type, $messages);
	}
}