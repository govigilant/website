<?php

namespace Vigilant\EmailList\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $list
 * @property string $email
 * @property ?array $data
 */
class SubmitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'list' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'data' => ['array'],
        ];
    }
}
