<?php

return [
    //file bắt buộc
    'table_main' => [
        'multiple' => 'login',
        'type_copy' => 0, // 0 : tạo thêm file, 1:  tạo thêm nhiều file, 2 :  tạo thêm sheet
        'base_sheet' => 'sample' //khi clone sheet cần
    ],
    //file bắt buộc
    'filed_export' => [
        'login',
        'root_id',
        'subject_root',
        'sum_hours'
    ],
    'cells' => [
        'member' => [
            'value' => 'login',
            'startRow' => '1',
            'columnStart' => 'D'
        ]
    ]
];