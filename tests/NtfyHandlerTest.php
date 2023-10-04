<?php


use Conkal\NtfyMonologHandler\NtfyHandler;
use PHPUnit\Framework\TestCase;

class NtfyHandlerTest extends TestCase
{

    public function test_it_should_send_notification()
    {
        $httpClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->setMethods(['request'])
            ->getMock();

        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->equalTo('http://ntfy.test'),
                $this->equalTo([
                    'headers' => [
                        'Content-Type' => 'text/plain',
                        'Title' => 'test',
                        'Priority' => 2,
                    ],
                    'body' => 'test'
                ])
            );

        $handler = new NtfyHandler('http://ntfy.test', $httpClient);
        $handler->handle(['message' => 'test', 'level' => 200,'extra'=>[],'context'=>[]]);

    }

}
