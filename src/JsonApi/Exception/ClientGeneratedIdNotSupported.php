<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ClientGeneratedIdNotSupported extends \Exception
{
    /**
     * @var string
     */
    private $clientGeneratedId;

    /**
     * @var string
     */
    private $reason;

    /**
     * @param string|null $clientGeneratedId
     * @param string $reason
     */
    public function __construct($clientGeneratedId, $reason = "")
    {
        parent::__construct(
            "Client generated ID " .
            ($clientGeneratedId ? "\"$clientGeneratedId\" " : "") .
            "is not supported!"
        );

        $this->clientGeneratedId = $clientGeneratedId;
        $this->reason = $reason;
    }

    /**
     * @return string|null
     */
    public function getClientGeneratedId()
    {
        return $this->clientGeneratedId;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
