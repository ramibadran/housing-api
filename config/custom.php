<?php
return [
    'apiLogDir'        => env('API_LOG_PATH', '/logs/api_logs'),
    'unitTestDir'      => env('UNIT_TEST_PATH', '/logs/unit-test-reporter'),
    'appEnv'           => env('APP_ENV','production'),
    'apiBaseURL'	   => env('API_BASE_URL','http://127.0.0.1:8000/api/third_part/'),
    
    'ApiMessages' => [
        '2000'  => [
            'customCode'      => '00',
            'customMessageEn' => 'Data Not Available',
            'customMessageAr' => 'Data Not Available',
        ],
        '2001'  => [
            'customCode'      => '01',
            'customMessageEn' => 'Opps!, Login credentials are either invalid or missing, please check the request and try again',
            'customMessageAr' => 'Opps!, Login credentials are either invalid or missing, please check the request and try again',
        ],
        '2002'  => [
            'customCode'      => '02',
            'customMessageEn' => 'Data returned Successfully',
            'customMessageAr' => 'Data returned Successfully',
        ],
        '2003'  => [
            'customCode'      => '03',
            'customMessageEn' => 'Opps!, Access denied, API has banned your ip address as this ip has no access to the api service',
            'customMessageAr' => 'Opps!, Access denied, API has banned your ip address as this ip has no access to the api service',
        ],
        '2004'  => [
            'customCode'      => '04',
            'customMessageEn' => 'Opps!, Issue while generating access token, Please try again',
            'customMessageAr' => 'Opps!, Issue while generating access token, Please try again',
        ],
        '2005'	=> [
            'customCode'      => '05',
            'customMessageEn' => 'Token has been expired, please generate new token and try again',
            'customMessageAr' => 'Token has been expired, please generate new token and try again'
        ],
        '2006'	=> [
            'customCode'      => '06',
            'customMessageEn' => 'Token is blacklisted, please generate new token and try again',
            'customMessageAr' => 'Token is blacklisted, please generate new token and try again'
        ],
        '2007'	=> [
            'customCode'      => '07',
            'customMessageEn' => 'Token is invalid, please generate new token and try again',
            'customMessageAr' => 'Token is invalid, please generate new token and try again'
        ],
        '2008'	=> [
            'customCode'      => '08',
            'customMessageEn' => 'Token is missing, please generate new token and try again',
            'customMessageAr' => 'Token is missing, please generate new token and try again'
        ],
        '2009'	=> [
            'customCode'      => '09',
            'customMessageEn' => 'System Error, please try again',
            'customMessageAr' => 'System Error, please try again'
        ],
        '2010'	=> [
            'customCode'      => '10',
            'customMessageEn' => 'Access token has been created successfully ,Please note that this token is valid for 60 mins only',
            'customMessageAr' => 'Access token has been created successfully ,Please note that this token is valid for 60 mins only',
        ],
        '2011'	=> [
            'customCode'      => '11',
            'customMessageEn' => 'User is not logged in, Please recreated access token and try again',
            'customMessageAr' => 'User is not logged in, Please recreated access token and try again'
        ],
        '2012'	=> [
            'customCode'      => '12',
            'customMessageEn' => 'Invalid encoded data, please check your request and try again',
            'customMessageAr' => 'Invalid encoded data, please check your request and try again'
        ],
        '2013'	=> [
            'customCode'      => '13',
            'customMessageEn' => 'Encrypted Data are either missing or empty, please check your request and try again',
            'customMessageAr' => 'Encrypted Data is either missing or empty, please check your request and try again'
        ],
        '2014'	=> [
            'customCode'      => '14',
            'customMessageEn' => 'Opps!, Login credentials are either invalid or missing, please check the request and try again',
            'customMessageAr' => 'Opps!, Login credentials are either invalid or missing, please check the request and try again'
        ],
        '2015'	=> [
            'customCode'      => '15',
            'customMessageEn' => 'Opps!, Your account is deactivated, please check your account and try again',
            'customMessageAr' => 'Opps!, Your account is deactivated, please check your account and try again'
        ],
        '2016'  => [
            'customCode'      => '16',
            'customMessageEn' => 'Opps!, hotel name or location is missing',
            'customMessageAr' => 'Opps!, hotel name or location is missing',
        ],
        '2017'  => [
            'customCode'      => '17',
            'customMessageEn' => 'Hotel Not Creted',
            'customMessageAr' => 'Hotel Not Creted',
        ],
        '2018'  => [
            'customCode'      => '18',
            'customMessageEn' => 'Hotel Creted Successfully',
            'customMessageAr' => 'Hotel Creted Successfully',
        ],
        '2019'  => [
            'customCode'      => '19',
            'customMessageEn' => 'Local Hotels Returned Successfully',
            'customMessageAr' => 'Local Hotels Returned Successfully',
        ]
    ]
];