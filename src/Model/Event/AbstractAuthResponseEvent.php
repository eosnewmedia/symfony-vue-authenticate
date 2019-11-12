<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Model\Event;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractAuthResponseEvent implements StoppableEventInterface
{
    /**
     * @var array|null
     */
    private $authResponse;

    /**
     * @param array|null $authResponse
     */
    public function setAuthResponse(array $authResponse): void
    {
        $this->authResponse = $authResponse;
    }

    /**
     * @return array|null
     */
    public function getAuthResponse(): ?array
    {
        return $this->authResponse;
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
        return $this->authResponse !== null;
    }
}
