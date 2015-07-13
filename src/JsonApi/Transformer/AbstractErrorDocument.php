<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Error;

abstract class AbstractErrorDocument extends AbstractDocument
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    protected function transformContent(Criteria $criteria)
    {
        $content = parent::transformContent($criteria);

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
