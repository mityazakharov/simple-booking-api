<?php

namespace App\GraphQL\Directives;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class UniqueValidationDirective extends ValidationDirective
{
    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $model = $this->getModelClass();
        $table = (new $model())->getTable();

        return [
            'phone' => [Rule::unique($table, 'phone')->ignore($this->args['id'], 'id')],
            'email' => [Rule::unique($table, 'email')->ignore($this->args['id'], 'id')],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'phone.unique' => 'The chosen phone is not available.',
            'email.unique' => 'The chosen email is not unique.',
        ];
    }
}
