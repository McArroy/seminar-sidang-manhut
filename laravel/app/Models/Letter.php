<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
	use DeterministicEncryption;

	protected $primaryKey = "letterid";
	public $incrementing = false;
	protected $keyType = "string";

	protected $fillable =
	[
		"letterid",
		"letternumber",
		"letterdate",
		"moderator",
		"external_examiner",
		"chairman_session"
	];

	// List of attributes to encrypt deterministic
	protected $encryptDeterministic =
	[
		"letternumber",
		"moderator",
		"external_examiner",
		"chairman_session"
	];
}