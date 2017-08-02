<?php
return [
    'pageSize' => 20,
    'folderReport' => 'report/',
    'week' => [
        'minus' => [
            'Mon' => 0,
            'Tue' => 1,
            'Wed' => 2,
            'Thu' => 3,
            'Fri' => 4,
            'Sat' => 5,
            'Sun' => 6
        ],
        'plus' => [
            'Mon' => 6,
            'Tue' => 5,
            'Wed' => 4,
            'Thu' => 3,
            'Fri' => 2,
            'Sat' => 1,
            'Sun' => 0
        ]
        
    ],
    'name_default' => 'Own',
    'position_default' => 'DEV',
    'time_start_default' => '8:30',
    'time_end_default' => '17:30',
    'folder_template' => 'export_file/',
    //config console
    'project_id' => [2,3,4,6,40],
    'report_config' => [
        'error_spent_time' => [
            'value' => 1,
            'enable' => true,
            'message' => 'Lỗi chưa log time dùng cho task'
        ],
        'error_description_bug' => [
            'value' => 2,
            'enable' => true,
            'message' => 'Lỗi chưa nhập description'
        ],
        'error_due_date' => [
            'value' => 3,
            'enable' => true,
            'message' => 'Lỗi chưa chuyển trạng thái sang kết thúc'
        ],
        'error_total_hours' => [
            'value' => 4,
            'enable' => true,
            'message' => ''
        ],
        'error_other' => [
            'value' => 5,
            'enable' => true,
            'message' => 'Tạo task other khi vẫn đang có task được giao chưa nhập'
        ],
        'error_start_date' => [
            'value' => 6,
            'enable' => true,
            'message' => 'Lỗi chưa chuyển trạng thái tiếp nhận'
        ],
    ]
];
