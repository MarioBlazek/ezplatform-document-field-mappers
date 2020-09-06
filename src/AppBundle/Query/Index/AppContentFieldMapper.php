<?php


namespace AppBundle\Query\Index;

use eZ\Publish\SPI\Persistence\Content as SPIContent;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentFieldMapper;

class AppContentFieldMapper extends ContentFieldMapper
{
    public function accept(SPIContent $content)
    {
        dump('content');
    }

    public function mapFields(SPIContent $content)
    {
        // TODO: Implement mapFields() method.
    }

}
