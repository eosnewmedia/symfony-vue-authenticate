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
class GoogleController extends AbstractAuthenticationController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function authenticate(Request $request): Response
    {
        try {
            //@todo
            return $this->createEventResponse(
                new GoogleUserLoggedIn()
            );
        } catch (Throwable $e) {
            return $this->createErrorResponse($e);
        }
    }
}
