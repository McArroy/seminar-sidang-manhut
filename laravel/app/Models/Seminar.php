<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
	protected $encryptDeterministic1 =
	[
		"useridnumber",
		"supervisor1",
		"supervisor2"
	];

	protected $encryptDeterministic2 =
	[
		"studyprogram",
		"department",
		"title",
		"link",
		"comment"
	];

	// Encrypt values before saving
	public function setAttribute($key, $value)
	{
		if (in_array($key, $this->encryptDeterministic1) && $value !== null)
			$value = $this->encryptDeterministic(trim($value));
		else if (in_array($key, $this->encryptDeterministic2) && $value !== null)
			$value = $this->encryptDeterministic(trim($value));
		else if (in_array($key, $this->encrypted) && $value !== null)
			$value = Crypt::encryptString($value);

		return parent::setAttribute($key, $value);
	}
	
	// Decrypt values when accessing
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);

		if ((in_array($key, $this->encryptDeterministic1) || in_array($key, $this->encryptDeterministic2)) && $value !== null)
		{
			try
			{
				return $this->decryptDeterministic($value);
			}
			catch (\Exception $e)
			{
				// optionally log: corrupted or already-decrypted value
				return $value;
			}
		}
		else if (in_array($key, $this->encrypted) && $value !== null)
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