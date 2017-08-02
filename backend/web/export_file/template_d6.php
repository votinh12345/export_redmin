<?php

return [
    //file bắt buộc
    'table_main' => [
        'multiple' => 'login',
        'type_copy' => 2, // 0 : tạo thêm file, 1:  tạo thêm nhiều file, 2 :  tạo thêm sheet
        'base_sheet' => 'sample', //khi clone sheet cần
        'cell_special' => []
    ],
    //file bắt buộc
    'filed_export' => [
        'login',
        'spent_on',
        'firstname',
        'lastname',
        'subject',
        'name'
    ],
    'row' => [
        'positon_start' => 'A',
        'row_start' => 8,
        'row_end' => 65,
        'delete_row' => false,
        'total_column_export' => 40
    ],
    'row_special' => [
        'positon_start' => 'E',
        'row_start' => 70,
        'value' => ''
    ]
];