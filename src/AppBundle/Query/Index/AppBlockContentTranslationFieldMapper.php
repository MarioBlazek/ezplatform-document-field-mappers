<?php


namespace AppBundle\Query\Index;

use eZ\Publish\SPI\Persistence\Content as SPIContent;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentTranslationFieldMapper;

class AppBlockContentTranslationFieldMapper extends ContentTranslationFieldMapper
{
    public function accept(SPIContent $content, $languageCode)
    {
        dump('block-content-translation');
    }

    public function mapFields(SPIContent $content, $languageCode)
    {
        // TODO: Implement mapFields() method.
    }

}
