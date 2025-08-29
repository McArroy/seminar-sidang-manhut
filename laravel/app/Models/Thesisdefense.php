<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Database\Eloquent\Model;

class Thesisdefense extends Model
{
	use DeterministicEncryption;

	protected $primaryKey = "thesisdefenseid";
	public $incrementing = false;
	protected $keyType = "string";

	protected $fillable =
	[
		"thesisdefenseid",
		"useridnumber",
		"semester",
		"address",
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
		"address",
		"place"
	];

	// List of attributes to encrypt deterministic
	protected $encryptDeterministic =
	[
		"useridnumber",
		"semester",
		"supervisor1",
		"supervisor2",
		"title",
		"link",
		"comment"
	];
}