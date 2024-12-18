<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], $this->messages())->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }

    public function messages(){
        return [
        'name.required' => 'お名前を入力してください',
        'name.string' => 'お名前は文字形式で入力してください',
        'name.max' => 'お名前は255文字以内で入力してください',
        'email.required' => 'メールアドレスを入力してください',
        'email.string' => 'メールアドレスは文字形式で入力してください',
        'email.email' => 'メールアドレスはメール形式で入力してください',
        'email.max' => 'メールアドレスは255文字以内で入力してください',
        'password.required' => 'パスワードを入力してください',
        'password.string' => 'パスワードは文字形式で入力してください',
        'password.min' => 'パスワードは8文字以上でで入力してください',
        'password.confirmed' => '確認用パスワードが一致しません'
        ];
    }
}
