<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait DeterministicEncryption
{
	public static function encryptDeterministic($value)
	{
		$key = hash("sha256", Config::get("app.key"), true);
		$iv = substr($key, 0, 16);

		return base64_encode(openssl_encrypt($value, "AES-256-CBC", $key, 0, $iv));
	}

	public function decryptDeterministic($cipher)
	{
		$key = hash("sha256", Config::get("app.key"), true);
		$iv = substr($key, 0, 16);

		return openssl_decrypt(base64_decode($cipher), "AES-256-CBC", $key, 0, $iv);
	}
}