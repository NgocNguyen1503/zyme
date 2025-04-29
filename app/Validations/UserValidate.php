<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class UserValidate 
{
    public static function validate($request)
    {
        return $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|max:255'
        ], [
            'name.required' => '編集者の名前を入力します。',
            'name.max' => '最大 255 文字を入力します。',
            'email.required' => '編集者のメールアドレスを入力します。',
            'email.max' => '最大 255 文字を入力します。',
            'password.required' => 'パスワードを入力する。',
            'password.max' => '最大 255 文字を入力します。'
        ]);

    }
}