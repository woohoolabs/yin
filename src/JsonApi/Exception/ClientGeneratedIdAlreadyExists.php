<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ClientGeneratedIdAlreadyExists extends \Exception
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
            "already exists!"
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
