<?php

return [
    'required' => ':attributeは必須項目です。',
    'email' => ':attributeには有効なメールアドレスを入力してください。',
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください。',
    ],
    'confirmed' => ':attributeが一致しません。',
    'unique' => 'この:attributeは既に登録されています。',
    'max' => [
        'string' => ':attributeは:max文字以内で入力してください。',
    ],


    'attributes' => [
        'name'                  => '名前',
        'email'                 => 'メールアドレス',
        'password'              => 'パスワード',
        'password_confirmation' => 'パスワード（確認）',
    ],
];