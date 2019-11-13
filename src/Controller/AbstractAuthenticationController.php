<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Eos\Bundle\VueAuthenticate\Model\Event\AbstractResponseEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractAuthenticationController
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param object $event
     */
    protected function dispatch(object $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getRequestBody(Request $request): array
    {
        return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param AbstractResponseEvent $event
     * @param string $errorMessage
     * @return Response
     */
    protected function createEventResponse(
        AbstractResponseEvent $event,
        string $errorMessage = 'User login failed.'
    ): Response {
        $this->dispatch($event);

        if (!$event->getResponse()) {
            throw new RuntimeException($errorMessage);
        }

        return $event->getResponse();
    }

    /**
     * @param Throwable $e
     * @param int $httpStatus
     * @return Response
     */
    protected function createErrorResponse(Throwable $e, int $httpStatus = Response::HTTP_UNAUTHORIZED): Response
    {
        return new JsonResponse(['error' => $e->getMessage()], $httpStatus);
    }
}
