<?php

namespace App\Http\Controllers;

use DateTime;

class DateIndoFormatterController
{
	protected static array $monthNames =
	[
		1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni",
		"Juli", "Agustus", "September", "Oktober", "November", "Desember"
	];

	protected static array $dayNames =
	[
		"Sunday" => "Minggu",
		"Monday" => "Senin",
		"Tuesday" => "Selasa",
		"Wednesday" => "Rabu",
		"Thursday" => "Kamis",
		"Friday" => "Jumat",
		"Saturday" => "Sabtu"
	];

	/**
	 * Format date into Indonesian: e.g. "Jumat / 1 Agustus 2025"
	 */
	public static function Full(DateTime|string $date, int $type = 0) : string
	{
		$date = self::EnsureDateTime($date);

		$dayName = self::$dayNames[$date->format("l")];
		$day = $date->format("j");
		$month = self::$monthNames[(int) $date->format("n")];
		$year = $date->format("Y");

		if ($type === 0)
			return "$dayName / $day $month $year";
		else if ($type === 1)
			return "$dayName, $day $month $year";
	}

	/**
	 * Format current date into Indonesian: e.g. "1 Agustus 2025"
	 */
	public static function Today() : string
	{
		return self::simple(new DateTime());
	}

	/**
	 * Format date as: "1 Agustus 2025"
	 */
	public static function Simple(DateTime|string $date) : string
	{
		$date = self::ensureDateTime($date);

		return $date->format("j") . " " .
			self::$monthNames[(int) $date->format("n")] . " " .
			$date->format("Y");
	}

	/**
	 * Helper to convert string to DateTime
	 */
	protected static function EnsureDateTime(DateTime|string $date) : DateTime
	{
		return $date instanceof DateTime ? $date : new DateTime($date);
	}
}