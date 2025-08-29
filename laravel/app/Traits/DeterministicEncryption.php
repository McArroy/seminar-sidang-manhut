<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

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

	public function setAttribute($key, $value)
	{
		if (is_null($value) || $value === "" || (is_array($value) && empty($value)))
			return parent::setAttribute($key, null);

		if (property_exists($this, "encryptDeterministic") && in_array($key, $this->encryptDeterministic))
		{
			if (is_array($value))
				$value = array_map(function($item)
				{
					return $this->encryptDeterministic(trim($item));
				}, $value);
			else
				$value = $this->encryptDeterministic(trim($value));
		}
		else if (property_exists($this, "encrypted") && in_array($key, $this->encrypted))
		{
			$value = Crypt::encryptString($value);
		}

		return parent::setAttribute($key, $value);
	}

	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);
		$notEmpty = (!is_null($value) && $value !== "");

		if (property_exists($this, "encryptDeterministic") && in_array($key, $this->encryptDeterministic) && $notEmpty)
		{
			if (is_array($value))
			{
				return array_map(function($item)
				{
					try
					{
						return $this->decryptDeterministic($item);
					}
					catch (\Exception $e)
					{
						return $item;
					}
				}, $value);
			}
			else
			{
				try
				{
					return $this->decryptDeterministic($value);
				}
				catch (\Exception $e)
				{
					return $value;
				}
			}
		}
		else if (property_exists($this, "encrypted") && in_array($key, $this->encrypted) && $notEmpty)
		{
			try
			{
				return Crypt::decryptString($value);
			}
			catch (\Exception $e)
			{
				return $value;
			}
		}

		return $value;
	}
}