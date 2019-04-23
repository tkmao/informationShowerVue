<?php

return [
    'projectDisplayMax' => 1,
    'undefineProjectId' => 999,
    'userType' => [
        'manager' => 1,
        'employee' => 2,
        'contractor' => 3,
        'parttime' => 4,
        'intern' => 5,
    ],
    'workingtimeType' => [
        'fluctuation' => [
            'id' => 1,
            'text' => '勤務日数により変動',
        ],
        'fix' => [
            'id' => 2,
            'text' => '固定勤務時間',
        ],
    ],
    'is_admin' => [
        'general' => '一般ユーザ',
        'is_admin' => '管理者',
    ],
];
