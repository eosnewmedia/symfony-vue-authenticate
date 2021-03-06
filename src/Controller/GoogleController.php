<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Eos\Bundle\VueAuthenticate\Model\Event\GoogleUserLoggedIn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class GoogleController extends AbstractOAuthController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function authenticate(Request $request): Response
    {
        try {
            $requestBody = $this->getRequestBody($request);

            $response = $this->sendOAuthRequest(
                $this->createUri('https://accounts.google.com/o/oauth2/token'),
                [
                    'code' => $requestBody['code'],
                    'redirect_uri' => $requestBody['redirectUri'],
                    'grant_type' => 'authorization_code'
                ]
            );

            return $this->createEventResponse(
                new GoogleUserLoggedIn(
                    $response['access_token'],
                    $response['expires_in'],
                    $response['id_token']
                )
            );
        } catch (Throwable $e) {
            return $this->createErrorResponse($e);
        }
    }
}
