<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Model\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractResponseEvent implements StoppableEventInterface
{
    /**
     * @var Response|null
     */
    private $response;

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Is propagation stopped?
     *
     * This will typically only be used by the Dispatcher to determine if the
     * previous listener halted propagation.
     *
     * @return bool
     *   True if the Event is complete and no further listeners should be called.
     *   False to continue calling listeners.
     */
    public function isPropagationStopped(): bool
    {
        return $this->response !== null;
    }
}
