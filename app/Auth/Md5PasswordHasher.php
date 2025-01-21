<?php

namespace App\Auth;

use Illuminate\Contracts\Hashing\Hasher;

class Md5PasswordHasher implements Hasher
{
    public function info($hashedValue)
    {
        return password_get_info($hashedValue);
    }

    public function make($value, array $options = [])
    {
        return md5($value);
    }

    public function check($value, $hashedValue, array $options = [])
    {
        return md5($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return true;
    }
}
