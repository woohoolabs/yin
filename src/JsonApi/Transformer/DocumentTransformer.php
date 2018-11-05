<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

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

    public function transformFullDocument(SuccessfulDocumentTransformation $transformation): SuccessfulDocumentTransformation
    {
        $transformation = clone $transformation;

        $transformation->document->initializeTransformation($transformation);
        $this->transformBaseMembers($transformation);
        $this->transformDataMembers($transformation);
        $transformation->document->clearTransformation();

        return $transformation;
    }

    public function transformMetaDocument(SuccessfulDocumentTransformation $transformation): SuccessfulDocumentTransformation
    {
        $transformation = clone $transformation;

        $transformation->document->initializeTransformation($transformation);
        $this->transformBaseMembers($transformation);
        $transformation->document->clearTransformation();

        return $transformation;
    }

    public function transformRelationshipDocument(SuccessfulDocumentTransformation $transformation): SuccessfulDocumentTransformation
    {
        $transformation = clone $transformation;

        $transformation->document->initializeTransformation($transformation);
        $this->transformBaseMembers($transformation);
        $this->transformDataMembers($transformation);
        $transformation->document->clearTransformation();

        return $transformation;
    }

    private function transformBaseMembers(SuccessfulDocumentTransformation $transformation): void
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

    private function transformDataMembers(SuccessfulDocumentTransformation $transformation): void
    {
        $data = $transformation->document->getData($transformation, $this->resourceTransformer);

        $transformation->result["data"] = $data->transformPrimaryData();

        if ($data->hasIncludedResources() || $transformation->request->hasIncludedRelationships()) {
            $transformation->result["included"] = $data->transformIncluded();
        }
    }
}
