<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

class CriteriaParser
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function parseCriteria(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();

        $includes = isset($query["includes"]) ? $this->parseIncludes($query["includes"]): [];
        $fields = isset($query["fields"]) ? $this->parseIncludes($query["fields"]): [];
    }

    /**
     * @param string $string
     * @return array
     */
    public function parseIncludes($string)
    {
        $includes = explode(",", $string);

        return $includes !== false ? $includes : [];
    }

    /**
     * @param array $fields
     * @return array
     */
    public function parseFields(array $fields)
    {
        $includes = explode(",", $fields);


        return $includes !== false ? $includes : [];
    }
}
