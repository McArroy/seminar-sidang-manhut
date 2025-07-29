<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
		"link",
		"comment",
		"status"
	];

	// List of attributes to encrypt
	protected $encrypted =
	[
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
		"comment"
	];

	// Encrypt values before saving
	public function setAttribute($key, $value)
	{
		if (in_array($key, $this->encrypted) && $value !== null)
			$value = Crypt::encryptString($value);

		return parent::setAttribute($key, $value);
	}
	
	// Decrypt values when accessing
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);

		if (in_array($key, $this->encrypted) && $value !== null)
		{
			try
			{
				return Crypt::decryptString($value);
			}
			catch (\Exception $e)
			{
				// optionally log: corrupted or already-decrypted value
				return $value;
			}
		}

		return $value;
	}
}