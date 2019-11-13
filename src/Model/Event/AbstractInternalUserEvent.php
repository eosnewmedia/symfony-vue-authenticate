<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Model\Event;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class AbstractInternalUserEvent extends AbstractResponseEvent
{
    /**
     * @var array
     */
    private $requestBody;

    /**
     * @param array $requestBody
     */
    public function __construct(array $requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * @return array
     */
    public function getRequestBody(): array
    {
        return $this->requestBody;
    }
}
