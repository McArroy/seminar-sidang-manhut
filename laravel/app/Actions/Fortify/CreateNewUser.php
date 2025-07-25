<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
	use PasswordValidationRules;

	/**
	 * Validate and create a newly registered user.
	 *
	 * @param  array<string, string>  $input
	 */
	public function create(array $input) : User
	{
		Validator::make($input,
		[
			"username" => ["required", "string", "max:255"],
			"useridnumber" => ["required", "string", "max:255", "unique:users"],
			"userrole" => ["required", "string", "max:65"],
			"password" => $this->passwordRules()
		])->validate();

		return User::create(
		[
			"username" => $input["username"],
			"useridnumber" => $input["useridnumber"],
			"userrole" => $input["userrole"],
			"password" => Hash::make($input["password"])
		]);
	}
}