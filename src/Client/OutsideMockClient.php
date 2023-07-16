<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OutsideMockClient
{
    protected string $baseUri;

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri(string $baseUri): void
    {
        $this->baseUri = $baseUri;
    }

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
                        // 3rd partylere iletme middleware.
                        return new Response(200, body: json_encode([
                            'status' => 'OK'
                        ]));
                    }
                );
            };
        });
        $client = new Client([
            'base_uri' => $this->getBaseUri(),
            'handler' => $handlerStack,
        ]);

        return $client->request('POST', '', ['json' => $contentData, 'expect' => false]);

    }

}
