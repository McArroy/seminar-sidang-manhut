<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
	public function run() : void
	{
		User::firstOrCreate(
		["useridnumber"	=> "akun-admin-manhut-332211"],
		[
			"userid"		=> (string)Str::uuid(),
			"username"		=> "Admin",
			"userrole"		=> "admin",
			"password"		=> Hash::make("akunadminmanhutpusat123"),
			"is_active"		=> 1
		]);
	}
}