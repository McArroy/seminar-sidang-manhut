<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
	use DeterministicEncryption;

	protected $primaryKey = "academicid";
	public $incrementing = false;
	protected $keyType = "string";

	protected $fillable =
	[
		"academicid",
		"academictype",
		"useridnumber",
		"studyprogram",
		"department",
		"semester",
		"address",
		"lecturers",
		"date",
		"time",
		"room",
		"title",
		"link",
		"comment",
		"is_accepted",
		"is_completed"
	];

	protected $casts =
	[
		"lecturers" => "array"
	];

	// List of attributes to encrypt
	protected $encrypted =
	[
		"address"
	];

	// List of attributes to encrypt deterministic
	protected $encryptDeterministic =
	[
		"useridnumber",
		"title",
		"lecturers",
		"link",
		"comment"
	];
}