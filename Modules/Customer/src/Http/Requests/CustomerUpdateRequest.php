<?php

namespace Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LVR\CreditCard\CardNumber;

class CustomerUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customerId' => 'required|integer|exists:customers,id',
            //at least one of the following fields should be filled
            'firstname' => ['sometimes', 'required_without_all:lastName,phoneNumber,email,dateOfBirth,bankAccountNumber', 'string', 'max:255', Rule::unique('customers')->where(function ($query) {
                return $query->where('lastname', $this->lastname)
                    ->where('dateOfBirth', $this->dateOfBirth);
            })->ignore($this->id)],
            'lastName' => 'sometimes|required_without_all:firstName,phoneNumber,email,dateOfBirth,bankAccountNumber|string|max:255',
            'dateOfBirth' => 'sometimes|required_without_all:firstName,lastName,phoneNumber,email,bankAccountNumber|date',
            'phoneNumber' => 'sometimes|required_without_all:firstName,lastName,email,dateOfBirth,bankAccountNumber|string|phone:US,IR',
            'email' => ['sometimes', 'required_without_all:lastName,phoneNumber,firstName,dateOfBirth,bankAccountNumber', 'email', 'max:255', Rule::unique('customers')->ignore($this->customerId)],
            'bankAccountNumber' => ['sometimes', 'required_without_all:lastName,phoneNumber,email,dateOfBirth,bankAccountNumber', 'string', new CardNumber],
        ];
    }
}
