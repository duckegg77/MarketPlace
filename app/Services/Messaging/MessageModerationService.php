<?php

namespace App\Services\Messaging;

class MessageModerationService
{
    public function shouldFlag(string $text): bool
    {
        $badWords = ['scam', 'fraud'];

        foreach ($badWords as $badWord) {
            if (str_contains(strtolower($text), $badWord)) {
                return true;
            }
        }

        return false;
    }
}
