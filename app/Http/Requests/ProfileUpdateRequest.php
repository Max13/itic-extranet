<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Ldap\Unique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'mail' => ['email', 'max:255', (new Unique(User::class))->ignore($this->user()->objectGuid)],
        ];
    }
}
