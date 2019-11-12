<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use Eos\Bundle\VueAuthenticate\Model\Event\TwitterUserLoggedIn;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class TwitterController extends AbstractAuthenticationController
{
    /**
     * @var TwitterOAuth
     */
    private $twitter;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param TwitterOAuth $twitter
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, TwitterOAuth $twitter)
    {
        parent::__construct($eventDispatcher);
        $this->twitter = $twitter;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function authenticate(Request $request): Response
    {
        try {
            $requestBody = $this->getRequestBody($request);

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

            $this->dispatch($loginEvent);

            if (!$loginEvent->getAuthResponse()) {
                throw new RuntimeException('User data could not be fetched!');
            }

            return new JsonResponse($loginEvent->getAuthResponse());
        } catch (Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }
}
