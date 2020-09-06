<?php

declare(strict_types=1);

namespace AppBundle\Query\Index;

use eZ\Publish\SPI\Persistence\Content as SPIContent;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentFieldMapper;
use eZ\Publish\SPI\Persistence\Content\Handler as ContentHandler;
use eZ\Publish\SPI\Persistence\Content\Type\Handler as ContentTypeHandler;
use eZ\Publish\SPI\Persistence\Content\Location\Handler as LocationHandler;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Persistence\Content\Type as ContentType;
use eZ\Publish\SPI\Persistence\Content\Field as ContentField;
use eZ\Publish\SPI\Search;
use RuntimeException;
use DOMDocument;
use DOMNode;

class ArticleChildrenMapper extends ContentFieldMapper
{
    private const NG_ARTICLE_CONTENT_TYPE_ID = 44;
    private const IMAGE_CONTENT_TYPE = 'image';

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Type\Handler
     */
    protected $contentHandler;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Location\Handler
     */
    protected $locationHandler;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Type\Handler
     */
    protected $contentTypeHandler;

    public function __construct(
        ContentHandler $contentHandler,
        LocationHandler $locationHandler,
        ContentTypeHandler $contentTypeHandler
    )
    {
        $this->contentHandler = $contentHandler;
        $this->locationHandler = $locationHandler;
        $this->contentTypeHandler = $contentTypeHandler;
    }

    public function accept(SPIContent $content)
    {
        return $content->versionInfo->contentInfo->contentTypeId == self::NG_ARTICLE_CONTENT_TYPE_ID;
    }

    public function mapFields(SPIContent $content)
    {
        $mainLocationId = $content->versionInfo->contentInfo->mainLocationId;
        $mainContentId = $content->versionInfo->contentInfo->id;

        $result = $this->locationHandler->loadSubtreeIds($mainLocationId);

        $fields = [];
        foreach ($result as $locationId => $contentId) {

            if ((int)$contentId === (int)$mainContentId) {
                continue;
            }

            $location = $this->locationHandler->load($locationId);
            $content = $this->contentHandler->load($location->contentId);
            $contentType = $this->contentTypeHandler->load($content->versionInfo->contentInfo->contentTypeId);

            if ($contentType->identifier !== self::IMAGE_CONTENT_TYPE) {
                continue;
            }

            $name = $this->extractField($content, $contentType, 'name');
            $caption = $this->extractField($content, $contentType, 'caption');

            $fields[] = new Search\Field(
            'meta_content__text',
                $name->value->data,
                new Search\FieldType\TextField()
            );

            $fields[] = new Search\Field(
                'meta_content__text',
                $this->process($caption),
                new Search\FieldType\TextField()
            );
        }

        return $fields;
    }

    private function extractField(Content $content, ContentType $contentType, $identifier): ContentField
    {
        $fieldDefinitionId = $this->getFieldDefinitionId($contentType, $identifier);
        foreach ($content->fields as $field) {
            if ($field->fieldDefinitionId === $fieldDefinitionId) {
                return $field;
            }
        }
        throw new RuntimeException(
            "Could not extract field '{$identifier}'"
        );
    }
    private function getFieldDefinitionId(ContentType $contentType, $identifier): int
    {
        foreach ($contentType->fieldDefinitions as $fieldDefinition) {
            if ($fieldDefinition->identifier === $identifier) {
                return $fieldDefinition->id;
            }
        }
        throw new RuntimeException(
            "Could not extract field definition '{$identifier}'"
        );
    }

    private function process(ContentField $field): string
    {
        $document = new DOMDocument();
        $document->loadXML($field->value->data);

        return $this->extractText($document->documentElement);
    }

    private function extractText(DOMNode $node): string
    {
        $text = '';

        if ($node->childNodes) {
            foreach ($node->childNodes as $child) {
                $text .= $this->extractText($child);
            }
        } else {
            $text .= $node->nodeValue . ' ';
        }

        return trim($text);
    }

}
