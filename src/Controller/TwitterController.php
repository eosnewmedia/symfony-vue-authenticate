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
class TwitterController extends AbstractAuthController
{
    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function authenticate(Request $request): Response
    {
        if (!$request->request->has('oauth_token')) {
            $oauth = new TwitterOAuth('', '');
            $response = $oauth->oauth('oauth/request_token', ['oauth_callback' => '']);

            return new JsonResponse([
                'oauth_token' => $response['oauth_token'],
                'oauth_token_secret' => $response['oauth_token_secret'],
                'oauth_callback_confirmed' => $response['oauth_callback_confirmed'],
            ]);
        }

    }
}
