<?php

namespace Customer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LVR\CreditCard\CardNumber;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required','string',Rule::unique('customers')->where(function ($query) {
                return $query->where('lastname', $this->lastname)
                    ->where('dateOfBirth', $this->dateOfBirth);
            })],
            'lastname' => 'required|string',
            'dateOfBirth' => 'required|string|date',
            'phoneNumber' => 'required|string|phone:US,IR',
            'email' => 'required|email',
            'bankAccountNumber' => ['required','string',new CardNumber]
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.unique' => 'A customer with the same first name, last name, and date of birth already exists.'
        ];
    }
}
