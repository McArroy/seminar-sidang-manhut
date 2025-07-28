<?php

namespace App\Http\Controllers;

use DateTime;

class DateIndoFormatter
{
	protected static array $MonthNames =
	[
		1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni",
		"Juli", "Agustus", "September", "Oktober", "November", "Desember"
	];

	protected static array $DayNames =
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
	public static function Full(DateTime|string $Date) : string
	{
		$Date = self::EnsureDateTime($Date);

		$DayName = self::$DayNames[$Date->format("l")];
		$Day = $Date->format("j");
		$Month = self::$MonthNames[(int) $Date->format("n")];
		$Year = $Date->format("Y");

		return "$DayName / $Day $Month $Year";
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
	public static function Simple(DateTime|string $Date) : string
	{
		$Date = self::ensureDateTime($Date);

		return $Date->format("j") . " " .
			self::$MonthNames[(int) $Date->format("n")] . " " .
			$Date->format("Y");
	}

	/**
	 * Helper to convert string to DateTime
	 */
	protected static function EnsureDateTime(DateTime|string $Date) : DateTime
	{
		return $Date instanceof DateTime ? $Date : new DateTime($Date);
	}
}