# Ntfy Monolog Handler

## Installation

```bash
composer require conkal/ntfy-monolog-handler
```

## Laravel Usage

```php
// config/logging.php
'channels' => [
  'ntfy' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),

            'handler' => Conkal\NtfyMonologHandler\NtfyHandler::class,
            'handler_with' => [
                'endPoint' => '<your ntfy endpoint>',
                'httpClient' => new \GuzzleHttp\Client(),
                'username' => '<your ntfy username>',
                'password' => '<your ntfy password>',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],
]
```
