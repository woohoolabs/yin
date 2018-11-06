<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;

/**
 * @internal
 */
final class DocumentTransformer
{
    /**
     * @var ResourceTransformer
     */
    private $resourceTransformer;

    public function __construct()
    {
        $this->resourceTransformer = new ResourceTransformer();
    }

    public function transformResourceDocument(ResourceDocumentTransformation $transformation): ResourceDocumentTransformation
    {
        $transformation = clone $transformation;

        $transformation->document->initializeTransformation($transformation);
        $this->transformBaseMembers($transformation);
        $this->transformDataMembers($transformation);
        $transformation->document->clearTransformation();

        return $transformation;
    }

    public function transformMetaDocument(ResourceDocumentTransformation $transformation): ResourceDocumentTransformation
    {
        $transformation = clone $transformation;

        $transformation->document->initializeTransformation($transformation);
        $this->transformBaseMembers($transformation);
        $transformation->document->clearTransformation();

        return $transformation;
    }

    public function transformRelationshipDocument(ResourceDocumentTransformation $transformation): ResourceDocumentTransformation
    {
        $transformation = clone $transformation;

        $transformation->document->initializeTransformation($transformation);
        $this->transformRelationship($transformation);
        $transformation->document->clearTransformation();

        return $transformation;
    }

    private function transformBaseMembers(ResourceDocumentTransformation $transformation): void
    {
        $jsonApi = $transformation->document->getJsonApi();
        if ($jsonApi !== null) {
            $transformation->result["jsonapi"] = $jsonApi->transform();
        }

        $meta = array_merge($transformation->document->getMeta(), $transformation->additionalMeta);
        if (empty($meta) === false) {
            $transformation->result["meta"] = $meta;
        }

        $links = $transformation->document->getLinks();
        if ($links !== null) {
            $transformation->result["links"] = $links->transform();
        }
    }

    private function transformDataMembers(ResourceDocumentTransformation $transformation): void
    {
        $data = $transformation->document->getData($transformation, $this->resourceTransformer);

        $transformation->result["data"] = $data->transformPrimaryData();

        if ($data->hasIncludedResources() || $transformation->request->hasIncludedRelationships()) {
            $transformation->result["included"] = $data->transformIncluded();
        }
    }

    private function transformRelationship(ResourceDocumentTransformation $transformation): void
    {
        $data = new SingleResourceData();

        $transformation->result = $transformation->document->getRelationshipData($transformation, $this->resourceTransformer, $data);

        if ($data->hasIncludedResources() || $transformation->request->hasIncludedRelationships()) {
            $transformation->result["included"] = $data->transformIncluded();
        }
    }
}
