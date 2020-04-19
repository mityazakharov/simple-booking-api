<?php

namespace App\Rules;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        // +7 Russia
        // City numbers: ^\+?7([348]\d{9})$
        // Mobile numbers: ^\+?7(9\d{9})$
        // All numbers: ^\+?7(\d{10})$
        return boolval(preg_match('/^\+?7(\d{10})$/', $value));
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The :attribute field contains an invalid number.';
    }
}