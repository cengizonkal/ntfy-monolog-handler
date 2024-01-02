<?php

namespace Conkal\NtfyMonologHandler;


use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class NtfyHandler extends AbstractProcessingHandler
{


    private $httpClient;
    private $endPoint;
    private $username;
    private $password;

    public function __construct($endPoint, $httpClient, $level = Level::Debug, $bubble = true, $username = null, $password = null)
    {
        $this->httpClient = $httpClient;
        $this->endPoint = $endPoint;
        $this->username = $username;
        $this->password = $password;
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $auth = base64_encode($this->username.':'.$this->password);
        $this->httpClient->request('POST', $this->endPoint, [
            'headers' => [
                'Content-Type' => 'text/plain',
                'Title' => $record['message'],
                'Priority' => $this->priority($record->level),
                'Authorization'=>'Basic '.$auth,
            ],
            'body' => $record['message']
        ]);
    }

    private function priority($level): int
    {
        return match ($level) {
            Level::Notice, Level::Debug, Level::Info => 2,
            Level::Error => 3,
            Level::Critical => 4,
            Level::Emergency, Level::Alert => 5,
            default => 1,
        };
    }
}
