<?php

return [
    //file bắt buộc
    'table_main' => [
        'multiple' => 'login',
        'type_copy' => 2, // 0 : tạo thêm file, 1:  tạo thêm nhiều file, 2 :  tạo thêm sheet
        'base_sheet' => 'sample' //khi clone sheet cần
    ],
    //file bắt buộc
    'filed_export' => [
        'login',
        'spent_on',
        'firstname',
        'lastname',
        'subject',
        'name'
    ]
];