<?php
return array(
    'utf_mode' =>
        array(
            'value' => true,
            'readonly' => true,
        ),
    'cache_flags' =>
        array(
            'value' =>
                array(
                    'config_options' => 3600,
                    'site_domain' => 3600,
                ),
            'readonly' => false,
        ),
    'cookies' =>
        array(
            'value' =>
                array(
                    'secure' => false,
                    'http_only' => true,
                ),
            'readonly' => false,
        ),
    'exception_handling' =>
        array(
            'value' =>
                array(
                    'debug' => true,                            // включить показ ошибок
                    'handled_errors_types' => 4437,
                    'exception_errors_types' => 4437,
                    'ignore_silence' => false,
                    'assertion_throws_exception' => true,
                    'assertion_error_type' => 256,
                    'log' => array(                             // настройки для логирования, свой логгер
                        'class_name' => 'MyLog',
                        'required_file' => 'classes/MyLog.php',
                        'settings' =>
                            array(
                                'file' => 'local/logs/error.log',
                                'log_size' => 1000000
                            ),
                    ),
                ),
            'readonly' => false,
        ),
    'crypto' =>
        array(
            'value' =>
                array(
                    'crypto_key' => 'zldnj6g2v9vp67trmtnvu5lsug3faxsj',
                ),
            'readonly' => true,
        ),
    'connections' =>
        array(
            'value' =>
                array(
                    'default' =>
                        array(
                            'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
                            'host' => 'localhost',
                            'database' => 'sitemanager',
                            'login' => 'bitrix0',
                            'password' => 'aAVW[VgVC7(P]lRXJm&M',
                            'options' => 2,
                        ),
                ),
            'readonly' => true,
        )
);