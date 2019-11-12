<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use Eos\Bundle\VueAuthenticate\Model\Event\TwitterUserLoggedIn;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class TwitterController
{
    /**
     * @var TwitterOAuth
     */
    private $twitter;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param TwitterOAuth $twitter
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(TwitterOAuth $twitter, EventDispatcherInterface $eventDispatcher)
    {
        $this->twitter = $twitter;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function authenticate(Request $request): Response
    {
        $requestBody = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!array_key_exists('oauth_token', $requestBody)) {

            $response = $this->twitter->oauth(
                'oauth/request_token',
                ['oauth_callback' => $requestBody['redirectUri']]
            );

            return new JsonResponse($response);
        }

        $response = $this->twitter->oauth(
            'oauth/access_token',
            [
                'oauth_token' => $requestBody['oauth_token'],
                'oauth_verifier' => $requestBody['oauth_verifier'],
            ]
        );

        $this->twitter->setOauthToken($response['oauth_token'], $response['oauth_token_secret']);

        $loginEvent = new TwitterUserLoggedIn();
        $this->eventDispatcher->dispatch($loginEvent);

        return $loginEvent->getAuthResponse() !== null ?
            new JsonResponse($loginEvent->getAuthResponse()) : new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }
}
