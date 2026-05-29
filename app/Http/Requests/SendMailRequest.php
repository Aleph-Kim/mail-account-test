<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mailer' => ['required', 'string'],
            'host' => ['required', 'string'],
            'port' => ['required', 'integer'],
            'username' => ['nullable', 'string'],
            'password' => ['nullable', 'string'],
            'encryption' => ['nullable', 'string'],
            'from_address' => ['required', 'email'],
            'from_name' => ['required', 'string'],
            'to_address' => ['required', 'array', 'min:1'],
            'to_address.*' => ['required', 'email'],
            'subject' => ['required', 'string'],
            'html_content' => ['required', 'string'],
        ];
    }
}
