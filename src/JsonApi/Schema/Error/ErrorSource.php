<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Error;

class ErrorSource
{
    /**
     * @var string
     */
    private $pointer;

    /**
     * @var string
     */
    private $parameter;

    public static function fromPointer(string $pointer): ErrorSource
    {
        return new ErrorSource($pointer, "");
    }

    public static function fromParameter(string $parameter): ErrorSource
    {
        return new ErrorSource("", $parameter);
    }

    public function __construct(string $pointer, string $parameter)
    {
        $this->pointer = $pointer;
        $this->parameter = $parameter;
    }

    public function getPointer(): string
    {
        return $this->pointer;
    }

    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     * @internal
     */
    public function transform(): array
    {
        $content = [];

        if ($this->getPointer() !== "") {
            $content["pointer"] = $this->getPointer();
        }

        if ($this->getParameter() !== "") {
            $content["parameter"] = $this->getParameter();
        }

        return $content;
    }
}
