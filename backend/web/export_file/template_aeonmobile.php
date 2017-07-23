<?php

return [
    //file bắt buộc
    'table_main' => [
        'multiple' => 'login',
        'type_copy' => 1, // 0 : tạo thêm file, 1:  tạo thêm nhiều file, 2 :  tạo thêm sheet
        'base_sheet' => '',
        'row_special' => [
            'mounth' => [
                'position' => 'L1',
                'value' => '6/30/2017',
                'flag_db' => 0
            ],
            'title' => [
                'position' => 'C5',
                'value' => '6月度 作業報告書（兼納品書）',
                'flag_db' => 0,
            ],
            'name' => [
                'position' => 'J9',
                'value' => 'full_name',
                'flag_db' => 1,
            ]
        ]
    ],
    //file bắt buộc
    'filed_export' => [
        'spent_on',
        'full_name',
        'sum_hours',
        'list_issues'
    ],
    'cells' => [
        'contents' => [
            'position' => 'H',
            'value' => 'list_issues',
            'startRow' => '11',
            'value_relation' => 'spent_on'
        ]
    ]
];