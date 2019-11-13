<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Eos\Bundle\VueAuthenticate\Model\Event\UserLogsIn;
use Eos\Bundle\VueAuthenticate\Model\Event\UserRegisters;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class InternalUserController extends AbstractAuthenticationController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        try {
            return $this->createEventResponse(
                new UserLogsIn($this->getRequestBody($request))
            );
        } catch (Throwable $e) {
            return $this->createErrorResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        try {
            return $this->createEventResponse(
                new UserRegisters($this->getRequestBody($request)),
                'User registration failed!'
            );
        } catch (Throwable $e) {
            return $this->createErrorResponse($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
