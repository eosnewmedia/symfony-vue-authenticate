<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Eos\Bundle\VueAuthenticate\Model\Event\FacebookUserLoggedIn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class FacebookController extends AbstractOAuthController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function authenticate(Request $request): Response
    {
        try {
            $requestBody = $this->getRequestBody($request);

            $this->sendOAuthRequest(
                $this->createUri('https://graph.facebook.com/v2.4/oauth/access_token'),
                [
                    'code' => $requestBody['code'],
                    'redirect_uri' => $requestBody['redirectUri']
                ],
                true
            );

            return $this->createEventResponse(
                new FacebookUserLoggedIn()
            );
        } catch (Throwable $e) {
            return $this->createErrorResponse($e);
        }
    }
}
