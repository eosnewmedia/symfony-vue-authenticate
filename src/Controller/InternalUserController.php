<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Eos\Bundle\VueAuthenticate\Model\Event\UserLogsIn;
use Eos\Bundle\VueAuthenticate\Model\Event\UserRegisters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;
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
            $loginEvent = new UserLogsIn($this->getRequestBody($request));

            $this->dispatch($loginEvent);

            if (!$loginEvent->getAuthResponse()) {
                throw new RuntimeException('User failed to log in!');
            }

            return new JsonResponse($loginEvent->getAuthResponse());
        } catch (Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        try {
            $registrationEvent = new UserRegisters($this->getRequestBody($request));

            $this->dispatch($registrationEvent);

            if (!$registrationEvent->getAuthResponse()) {
                throw new RuntimeException('User registration failed!');
            }

            return new JsonResponse($registrationEvent->getAuthResponse());
        } catch (Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
