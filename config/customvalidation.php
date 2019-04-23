<?php

return [

    /**
     * バリデーション関連で利用する定数
     */
    // 半角数字のみ
    'halfSizeNum' => '/^[0-9]+$/',
    // 半角英数のみ
    'halfSizeAlphaNum' => '/^[a-zA-Z0-9]+$/',
    // 半角英字のみ
    'halfSizeAlpha' => '/^[a-zA-Z]+$/',
    // 半角英数記号のみ
    'halfSizeAlphaNumSymbol' => '/^[!-~]+$/',
    // 半角カナ英数字記号のみ
    'halfSizeKanaAlphaNumSymbol' => '/^[!-~｡-ﾟ]+$/u',
    // 電話番号(ハイフン付き)
    'telWithHyphen' => '/^0\d{1,5}-\d{1,4}-\d{3,4}$/',
    // INT型最小値
    'intMinValue' => -2147483648,
    // INT型最大値
    'intMaxValue' => 2147483647,
    // INT型最小値
    'intUnsignedMinValue' => 0,
    // INT型最大値
    'intUnsignedMaxValue' => 4294967295,
    // TINYINT型最小値
    'tinyintMinValue' => -128,
    // TINYINT型最大値
    'tinyintMaxValue' => 127,
    // TINYINT型最小値
    'tinyintUnsignedMinValue' => 0,
    // TINYINT型最大値
    'tinyintUnsignedMaxValue' => 255,
];
