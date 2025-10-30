<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_]{5,20}$/',
                'not_regex:/^\d+$/',
                'not_regex:/^_/',
                'not_regex:/_$/',
                'not_in:admin,root,system',
                'unique:users,username',
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'not_regex:/123456|abcdef|password|qwerty/i',
                function ($attribute, $value, $fail) {
                    if (str_contains(strtolower($value), strtolower($this->input('username')))) {
                        $fail('Password tidak boleh mengandung username.');
                    }
                },
            ],
            'password_confirmation' => ['required', 'string'],
            'requested_role' => ['required', 'string', 'in:admin,sales,teknisi,inputer_sap,inputer_spk'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore (_), minimal 5 dan maksimal 20 karakter.',
            'username.not_regex' => 'Username tidak boleh hanya angka atau diawali/diakhiri dengan underscore (_).',
            'username.not_in' => 'Username tidak boleh menggunakan kata terlarang seperti admin, root, atau system.',
            'username.unique' => 'Username sudah digunakan.',

            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.lowercase' => 'Email harus dalam huruf kecil.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 64 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.letters' => 'Password harus mengandung minimal 1 huruf.',
            'password.mixed_case' => 'Password harus mengandung minimal 1 huruf besar dan 1 huruf kecil.',
            'password.numbers' => 'Password harus mengandung minimal 1 angka.',
            'password.symbols' => 'Password harus mengandung minimal 1 simbol spesial (!@#$%^&*).',
            'password.not_regex' => 'Password tidak boleh menggunakan urutan umum seperti 123456, abcdef, password, atau qwerty.',

            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.string' => 'Konfirmasi password harus berupa teks.',

            'requested_role.required' => 'Role yang diminta wajib dipilih.',
            'requested_role.string' => 'Role harus berupa teks.',
            'requested_role.in' => 'Role yang dipilih tidak valid.',
        ];
    }
}
