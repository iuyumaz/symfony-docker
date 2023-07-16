<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GoogleApiMockClient
{
    /**
     * @param $contentData
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @¢odeCoverageIgnore
     */
    public function makeMockRequest($contentData)
    {
        $mockHandler = new MockHandler([
            new Response(200)
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request) {
                        $contentsRequest = json_decode($request->getBody()->getContents(), true);
                        if (substr($contentsRequest['receipt'], -1) % 2 === 1) {
                            $dateTime = new \DateTime();
                            $dateTime->setTimezone(new \DateTimeZone("Etc/GMT-6"));
                            return new Response(200, body: json_encode([
                                'status' => true,
                                'expireTimestamp' => $dateTime->getTimestamp()
                            ]));
                        } else {
                            return new Response(400, body: json_encode([
                                'status' => false,
                            ]));
                        }
                    }
                );
            };
        });
        $client = new Client([
            'base_uri' => 'http://googleappinpurchase.com',
            'handler' => $handlerStack,
        ]);

        return $client->request('POST', '/purchase', ['json' => $contentData, 'expect' => false]);

    }

}
