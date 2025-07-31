<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public static function GetUsername(string $useridnumber)
	{
		$user = User::all()->filter(function($user) use ($useridnumber)
		{
			return $user->useridnumber === $useridnumber;
		})->first();

		return $user ? $user->username : null;
	}
}