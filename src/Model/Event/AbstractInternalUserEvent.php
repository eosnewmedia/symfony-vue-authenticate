<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Model\Event;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class AbstractInternalUserEvent extends AbstractAuthResponseEvent
{
    /**
     * @var array
     */
    private $request;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
