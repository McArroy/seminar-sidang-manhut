<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Letter extends Model
{
	use DeterministicEncryption;

	protected $primaryKey = "letterid";
	public $incrementing = false;
	protected $keyType = "string";

	protected $fillable =
	[
		"letterid",
		"academicid",
		"letternumber",
		"moderator",
		"letterdate",
		"supervisory_committee",
		"external_examiner",
		"chairman_session"
	];

	// List of attributes to encrypt deterministic
	protected $encryptDeterministic =
	[
		"letternumber",
		"moderator",
		"supervisory_committee",
		"external_examiner",
		"chairman_session"
	];

	// Encrypt values before saving
	public function setAttribute($key, $value)
	{
		if (in_array($key, $this->encryptDeterministic) && $value !== null)
			$value = $this->encryptDeterministic(trim($value));

		return parent::setAttribute($key, $value);
	}
	
	// Decrypt values when accessing
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);

		if ((in_array($key, $this->encryptDeterministic)) && $value !== null)
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

		return $value;
	}
}