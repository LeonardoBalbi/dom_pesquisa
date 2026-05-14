<?php

return [

    'temporary_file_upload' => [
        'disk' => 'local',

        'rules' => [
            'file',
            'max:102400', // 100MB
        ],

        'directory' => 'livewire-tmp',

        'middleware' => null,

        'preview_mimes' => [
            'png',
            'gif',
            'bmp',
            'svg',
            'wav',
            'mp4',
            'mov',
            'avi',
            'wmv',
            'mp3',
            'm4a',
            'jpg',
            'jpeg',
            'mpga',
            'webp',
            'wma',
            'pdf',
        ],

        'max_upload_time' => 50,
    ],

];
