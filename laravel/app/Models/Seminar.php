<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
	protected $primaryKey = "seminarid";
	public $incrementing = false;
	protected $keyType = "string";

	protected $fillable =
	[
		"seminarid",
		"useridnumber",
		"studyprogram",
		"department",
		"supervisor1",
		"supervisor2",
		"date",
		"time",
		"place",
		"title",
		"comment",
		"status"
	];
}