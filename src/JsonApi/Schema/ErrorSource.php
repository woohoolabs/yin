<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

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

    /**
     * @param string $pointer
     * @return $this
     */
    public static function fromPointer($pointer)
    {
        return new self($pointer, "");
    }

    /**
     * @param string $parameter
     * @return $this
     */
    public static function fromParameter($parameter)
    {
        return new self("", $parameter);
    }

    /**
     * @param string $pointer
     * @param string $parameter
     */
    public function __construct($pointer, $parameter)
    {
        $this->pointer = $pointer;
        $this->parameter = $parameter;
    }

    /**
     * @return array
     */
    public function transform()
    {
        $content = [];

        if ($this->getPointer()) {
            $content["pointer"] = $this->getPointer();
        }

        if ($this->getParameter()) {
            $content["parameter"] = $this->getParameter();
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getPointer()
    {
        return $this->pointer;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}
