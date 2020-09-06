<?php


namespace AppBundle\Query\Index;

use eZ\Publish\SPI\Persistence\Content\Location as SPILocation;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\LocationFieldMapper;

class AppLocationFieldMapper extends LocationFieldMapper
{
    public function accept(SPILocation $location)
    {
        dump('location');
    }

    public function mapFields(SPILocation $location)
    {
        // TODO: Implement mapFields() method.
    }
}
