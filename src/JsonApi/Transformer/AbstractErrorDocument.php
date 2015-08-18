<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;

abstract class AbstractErrorDocument extends AbstractDocument
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(ResponseInterface $response, $responseCode = null)
    {
        $response->getBody()->rewind();
        $response->getBody()->write(json_encode($this->transformContent()));
        $response = $response->withStatus($this->getResponseCode($responseCode));
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @param int $responseCode
     * @return int
     */
    protected function getResponseCode($responseCode)
    {
        if ($responseCode !== null) {
            return $responseCode;
        }

        if (count($this->errors) === 1) {
            return $this->errors[0]->getStatus();
        }

        $responseCode = 500;
        foreach ($this->errors as $error) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\Error $error */
            $roundedStatusCode = intval($error->getStatus() / 100) * 100;

            if (abs($error->getStatus() - $roundedStatusCode) >= 100) {
                $responseCode = $roundedStatusCode;
            }
        }

        return $responseCode;
    }

    /**
     * @return array
     */
    protected function transformContent()
    {
        $content = $this->transformBaseContent();

        // ERRORS
        if (empty($this->errors) === false) {
            foreach ($this->errors as $error) {
                /** @var \WoohooLabs\Yin\JsonApi\Schema\Error $error */
                $content["errors"][] = $error->transform();
            }
        }

        return $content;
    }
}
