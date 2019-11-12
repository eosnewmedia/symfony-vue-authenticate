<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Abraham\TwitterOAuth\TwitterOAuth;
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
     * @var string
     */
    private $consumerKey;

    /**
     * @var string
     */
    private $consumerSecret;


    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function authenticate(Request $request): Response
    {
        if (!$request->request->has('oauth_token')) {
            $oauth = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
            $response = $oauth->oauth(
                'oauth/request_token',
                ['oauth_callback' => $request->request->get('redirectUri')]
            );

            return new JsonResponse($response);
        }

        $oauth = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $response = $oauth->oauth(
            'oauth/access_token',
            [
                'oauth_token' => $request->request->get('oauth_token'),
                'oauth_verifier' => $request->request->get('oauth_verifier'),
            ]
        );

        return new JsonResponse($response);
    }
}
