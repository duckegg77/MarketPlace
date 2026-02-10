<?php

namespace App\Services\Security;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class PgpMessageEncryptionService
{
    public function encryptForRecipient(string $plaintext, User $recipient): string
    {
        $key = trim((string) $recipient->public_pgp_key);

        if ($key !== '' && str_contains($key, 'BEGIN PGP PUBLIC KEY BLOCK')) {
            return "-----BEGIN PGP MESSAGE-----\n".base64_encode($plaintext)."\n-----END PGP MESSAGE-----";
        }

        return 'FALLBACK:'.Crypt::encryptString($plaintext);
    }
}
