<?php

/**
 * システム共通で利用する項目ごとのバリデーションルールを記載
 * 画面ごとのルールは各Requestファイルを参照
 */

return [
    'id.*'               => 'bail|int_unsigned_type|integer',
    'id'               => 'bail|array',
    'group_id'         => 'bail|string|max_digit:256',
    'group_name'       => 'bail|string|max_digit:256',
    'name'             => 'bail|string', // テーブルにより桁数が異なるため桁数チェックは各項目でチェック
    'email'            => 'bail|email',
    'password'         => 'bail|half_size_alpha_num|max_digit:255',
    'active'           => 'bail|in:0,1',
    'company_id'       => 'bail|int_unsigned_type|integer',
    'month_of_years'   => 'bail|date_format:Y-m',
    'personal_text'    => 'bail|string|max_digit:10000',
    'information'      => 'bail|string|max_byte:4294967295',
    'title'            => 'bail|string|max_digit:256',
    'category_id'      => 'bail|int_unsigned_type|integer',
    'address'          => 'bail|string|max_digit:256',
    'phone'            => 'bail|tel_with_hyphen',
    'youtube_group_id' => 'bail|int_unsigned_type|integer',
    'comment'          => 'bail|string|max_byte:4294967295',
    'upload_file'      => 'bail|mimes:pdf|max:10240',
    'helpfulnesses'    => 'bail|array',
    'helpfulnesses.*'  => 'bail|int_unsigned_type|integer',
    'ids'              => 'bail|array',
    'ids.*'            => 'bail|int_unsigned_type|integer',
    'provide_regional_services' => 'bail|boolean',
];
