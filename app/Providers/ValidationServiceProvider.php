<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap custom validation rules.
     *
     * @return void
     */
    public function boot()
    {
        // Phone number
        Validator::extend(
            'phone',
            function ($attribute, $value, $parameters, $validator) {
                // +7 Russia
                // City numbers: ^\+?7([348]\d{9})$
                // Mobile numbers: ^\+?7(9\d{9})$
                // All numbers: ^\+?7(\d{10})$
                return boolval(preg_match('/^\+?7(\d{10})$/', $value));
            },
            'The :attribute field contains an invalid number.'
        );

        // Unique field value ignore self (use for update)
        Validator::extend(
            'unique_ignore_self',
            function ($attribute, $value, $parameters, $validator) {
                $parameters[2] = data_get($validator->getData(), 'id', null);

                return $validator->validateUnique($attribute, $value, $parameters);
            },
            'The chosen :attribute is not available.'
        );

        // MorphTo relation exists
        Validator::extend(
            'exists_morph',
            function ($attribute, $value, $parameters, $validator) {
                $type = Str::beforeLast($attribute, '.') . '.' . ($parameters[0] ?? 'type');

                $parameters[0] = data_get($validator->getData(), $type, null);
                $parameters[1] = $parameters[1] ?? 'id';

                return $validator->validateExists($attribute, $value, $parameters);
            },
            'The selected :attribute is invalid.'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
