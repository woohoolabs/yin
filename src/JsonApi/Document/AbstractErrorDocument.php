<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;

abstract class AbstractErrorDocument extends AbstractDocument
{
    /**
     * @var Error[]
     */
    protected $errors = [];

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Includes a new error in the error document.
     *
     * @param Error $error
     * @return $this
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Returns a response with a status code of $responseCode, containing all the provided members of the error
     * document. You can also pass additional meta information for the document in the $additionalMeta argument.
     */
    public function getResponse(
        SerializerInterface $serializer,
        ResponseInterface $response,
        int $responseCode = null,
        array $additionalMeta = []
    ): ResponseInterface {
        return $serializer->serialize($response,
            $this->getResponseCode($responseCode),
            $this->transformContent($additionalMeta)
        );
    }

    protected function getResponseCode(int $responseCode = null): int
    {
        if ($responseCode !== null) {
            return $responseCode;
        }

        if (count($this->errors) === 1) {
            return (int) $this->errors[0]->getStatus();
        }

        $responseCode = 500;
        foreach ($this->errors as $error) {
            /** @var Error $error */
            $roundedStatusCode = (int) (((int)$error->getStatus()) / 100) * 100;

            if (abs($responseCode - $roundedStatusCode) >= 100) {
                $responseCode = $roundedStatusCode;
            }
        }

        return $responseCode;
    }

    public function transformContent(array $additionalMeta = []): array
    {
        $content = $this->transformBaseContent($additionalMeta);

        if (empty($this->errors) === false) {
            foreach ($this->errors as $error) {
                /** @var Error $error */
                $content["errors"][] = $error->transform();
            }
        }

        return $content;
    }
}
