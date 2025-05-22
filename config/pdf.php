<?php

return [
    'mode'                  => 'utf-8',
    'format'               => 'A4',
    'author'               => '',
    'subject'              => '',
    'keywords'             => '',
    'creator'              => 'Laravel',
    'display_mode'         => 'fullpage',
    'tempDir'              => storage_path('app/public/temp'),
    
    'font_path' => base_path('resources/fonts/'),
    'font_data' => [
        'dejavu' => [
            'R'  => 'DejaVuSans.ttf',    // regular font
            'B'  => 'DejaVuSans-Bold.ttf',       // optional: bold font
            'I'  => 'DejaVuSans-Oblique.ttf',     // optional: italic font
            'BI' => 'DejaVuSans-BoldOblique.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,    // required for complicated scripts like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated scripts like Persian, Arabic
        ]
    ]
]; 