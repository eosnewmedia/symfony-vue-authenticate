<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

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
}
