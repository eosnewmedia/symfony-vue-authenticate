<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\Model\Event;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class GoogleUserLoggedIn extends AbstractResponseEvent
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var int
     */
    private $expiresIn;

    /**
     * @var string
     */

    private $idToken;

    /**
     * @param string $accessToken
     * @param int $expiresIn
     * @param string $idToken
     */
    public function __construct(string $accessToken, int $expiresIn, string $idToken)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->idToken = $idToken;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getIdToken(): string
    {
        return $this->idToken;
    }
}
