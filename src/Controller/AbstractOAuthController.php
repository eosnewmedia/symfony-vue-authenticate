<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractOAuthController extends AbstractAuthenticationController
{
    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @param UriFactoryInterface $uriFactory
     * @param StreamFactoryInterface $streamFactory
     * @param RequestFactoryInterface $requestFactory
     * @param ClientInterface $httpClient
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory,
        RequestFactoryInterface $requestFactory,
        ClientInterface $httpClient,
        EventDispatcherInterface $eventDispatcher,
        string $clientId,
        string $clientSecret
    ) {
        parent::__construct($eventDispatcher);
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
        $this->requestFactory = $requestFactory;
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @param string $uri
     * @return UriInterface
     */
    protected function createUri(string $uri): UriInterface
    {
        return $this->uriFactory->createUri($uri);
    }

    /**
     * @param UriInterface $uri
     * @param array $additionalParameters
     * @param bool $json
     * @return array
     * @throws Throwable
     */
    protected function sendOAuthRequest(UriInterface $uri, array $additionalParameters, $json = false): array
    {
        $parameters = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        if ($json) {
            $request = $this->requestFactory->createRequest('POST', $uri)
                ->withHeader('Content-Type', 'application/json')
                ->withBody(
                    $this->streamFactory->createStream(
                        json_encode(array_merge($parameters, $additionalParameters), JSON_THROW_ON_ERROR)
                    )
                );
        } else {
            $request = $this->requestFactory->createRequest('POST', $uri)
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
                ->withBody(
                    $this->streamFactory->createStream(
                        http_build_query(array_merge($parameters, $additionalParameters), '', '&')
                    )
                );


        }

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException($response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
