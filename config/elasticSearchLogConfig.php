<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => "stack",

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],
        'daily' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_lumen',
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ]
        ],
        'sms_log' => [
            'driver' => 'monolog',
            'level' => 'info',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_sms_log' ,
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ],
        ],
        'mail_log' => [
            'driver' => 'monolog',
            'level' => 'info',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_mail_log',
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ],
        ],
        'saga' => [
            'driver' => 'monolog',
            'level' => 'info',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_saga',
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ],
        ],
        'org_reg' => [
            'driver' => 'monolog',
            'level' => 'info',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_org_reg',
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ],
        ],
        'single' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_lumen',
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ],
        ],
        'slack' => [
            'driver' => 'monolog',
            'level' => 'critical',
            'handler' => \Monolog\Handler\ElasticsearchHandler::class,
            'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
            'formatter_with' => [
                'index' => env('ELASTIC_LOGS_INDEX') . '_lumen',
                'type' => '_doc',
            ],
            'handler_with' => [
                'client' => \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST')])->build(),
            ]
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],
        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],
        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],
        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],

];