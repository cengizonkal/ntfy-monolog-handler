<?php

namespace Conkal\NtfyMonologHandler;


use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class NtfyHandler extends AbstractProcessingHandler
{


    private $httpClient;
    private $endPoint;

    public function __construct($endPoint, $httpClient, $level = Logger::DEBUG, $bubble = true)
    {
        $this->httpClient = $httpClient;
        $this->endPoint = $endPoint;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $this->httpClient->request('POST', $this->endPoint, [
            'headers' => [
                'Content-Type' => 'text/plain',
                'Title' => $record['message'],
                'Priority' => $this->priority($record['level']),
            ],
            'body' => $record['message']
        ]);
    }

    private function priority($level): int
    {
        switch ($level) {
            case Logger::NOTICE:
            case Logger::WARNING:
            case Logger::INFO:
                return 2;
            case Logger::ERROR:
                return 3;
            case Logger::CRITICAL:
                return 4;
            case Logger::EMERGENCY:
            case Logger::ALERT:
                return 5;
            default:
                return 1;
        }
    }
}