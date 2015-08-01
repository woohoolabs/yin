<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ClientGeneratedIdNotSupported extends \Exception
{
    /**
     * @var string
     */
    private $clientGeneratedId;

    /**
     * @param string|null $clientGeneratedId
     */
    public function __construct($clientGeneratedId)
    {
        parent::__construct(
            "Client generated ID " .
            ($clientGeneratedId ? "\"$clientGeneratedId\" " : "") .
            "is not supported!"
        );

        $this->clientGeneratedId = $clientGeneratedId;
    }

    /**
     * @return string|null
     */
    public function getClientGeneratedId()
    {
        return $this->clientGeneratedId;
    }
}
