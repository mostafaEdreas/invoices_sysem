<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_users') || $this->user()->can('update_users')   ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return  [
           'name' => 'required|string|max:255',
           'email' => 'required|email|unique:users,email,'.$this->route('user') ,
            'password' => $this->isMethod('post') ? 'required|string|min:8|confirmed' : 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ];
    }
}
