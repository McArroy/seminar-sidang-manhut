<?php

namespace App\Models;

use App\Traits\DeterministicEncryption;
use Illuminate\Support\Str;
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
		"useridnumber",
		"username",
		"userrole",
		"password"
	];

	// List of attributes to encrypt
	protected $encrypted =
	[
		"userrole"
	];

	protected $encryptDeterministic =
	[
		"useridnumber",
		"username"
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
}