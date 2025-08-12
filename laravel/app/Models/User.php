<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens;
	use Notifiable;
	use DeterministicEncryption;

	protected $primaryKey = "userid";
	public $incrementing = false;
	protected $keyType = "string";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable =
	[
		"username",
		"useridnumber",
		"userrole",
		"password"
	];

	// List of attributes to encrypt
	protected $encrypted =
	[
		"userrole"
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden =
	[
		"password"
	];

	protected static function boot()
	{
		parent::boot();

		static::creating(function($user)
		{
			if (empty($user->userid))
				$user->userid = (string)Str::uuid();
		});
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts() : array
	{
		return
		[
			"password" => "hashed"
		];
	}

	// Encrypt values before saving
	public function setAttribute($key, $value)
	{
		if ($key === "useridnumber" && $value !== null)
			$value = $this->encryptDeterministic(strtolower(trim($value)));
		else if ($key === "username" && $value !== null)
			$value = $this->encryptDeterministic(trim($value));
		else if (in_array($key, $this->encrypted) && $value !== null)
			$value = Crypt::encryptString($value);

		return parent::setAttribute($key, $value);
	}
	
	// Decrypt values when accessing
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);

		if (($key === "useridnumber" || $key === "username") && $value !== null)
		{
			return $this->decryptDeterministic($value);
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