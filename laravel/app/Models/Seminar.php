<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
	use DeterministicEncryption;

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
		"link",
		"comment",
		"status"
	];

	// List of attributes to encrypt
	protected $encrypted =
	[
		"place"
	];

	// List of attributes to encrypt deterministic
	protected $encryptDeterministic =
	[
		"useridnumber",
		"studyprogram",
		"department",
		"supervisor1",
		"supervisor2",
		"title",
		"link",
		"comment"
	];
}