<?php

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class BepremsPasswordEncoder implements PasswordEncoderInterface
{
    public function encodePassword(string $raw, ?string $salt)
    {
        return hash('sha256', md5('#Gr33n' . $raw . 'p0!n7#'));
    }

    public function isPasswordValid(string $encoded, string $raw, ?string $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }

    public function needsRehash(string $encoded): bool
    {
        return false;
    }
}
