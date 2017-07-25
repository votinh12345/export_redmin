<?php

return [
    //file bắt buộc
    'table_main' => [
        'multiple' => '',
        'type_copy' => 0, // 0 : tạo thêm file, 1:  tạo thêm nhiều file, 2 :  tạo thêm sheet
        'base_sheet' => '', //khi clone sheet cần,
        'cell_special' => []
    ],
    //file bắt buộc
    'filed_export' => [
        'login',
        'root_id',
        'subject_root',
        'sum_hours'
    ],
    'row' => [
        'positon_start' => 'A',
        'row_start' => 1,
        'row_end' => null,
        'delete_row' => false,
        'total_column_export' => 7
    ],
    'row_special' => [
        'positon_start' => 'D',
        'row_start' => 12,
        'total_column_export' => 7
    ]
];