<?php


namespace AppBundle\Query\Index;

use eZ\Publish\SPI\Persistence\Content as SPIContent;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentTranslationFieldMapper;

class AppContentTranslationFieldMapper extends ContentTranslationFieldMapper
{
    public function accept(SPIContent $content, $languageCode)
    {
        return $content->versionInfo->contentInfo->id == 366;
    }

    public function mapFields(SPIContent $content, $languageCode)
    {
        return [];
    }
}
