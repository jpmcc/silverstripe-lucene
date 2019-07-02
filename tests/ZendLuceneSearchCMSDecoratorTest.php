<?php

class ZendSearchLuceneCMSDecoratorTest extends SapphireTest {

    static $fixture_file = 'lucene-silverstripe-plugin/tests/ZendSearchLuceneSearchableTest.yml';

    function testRebuildZendSearchLuceneIndex() {
        // Setup
        ContentController::remove_extension('ZendSearchLuceneContentController');
        SiteConfig::remove_extension('ZendSearchLuceneSiteConfig');
        LeftAndMain::remove_extension('ZendSearchLuceneCMSDecorator');
        SiteTree::remove_extension('ZendSearchLuceneSearchable');
        File::remove_extension('ZendSearchLuceneSearchable');
        ZendSearchLuceneSearchable::$pageLength = 10;
        ZendSearchLuceneSearchable::$alwaysShowPages = 3;   
        ZendSearchLuceneSearchable::$maxShowPages = 8;   
        ZendSearchLuceneSearchable::$encoding = 'utf-8';
        ZendSearchLuceneSearchable::$cacheDirectory = TEMP_FOLDER;
        ZendSearchLuceneWrapper::$indexName = 'Test';        

        ZendSearchLuceneSearchable::enable();
        
        $index = ZendSearchLuceneWrapper::getIndex(true);
        
        // Blank database
        $this->assertEquals( 0, $index->count() );

        // Count number of SiteTree and File objects
        $SiteTreeCount = DataObject::get('SiteTree')->count();
        $FileCount = DataObject::get('File')->count();
        $IndexableCount = $SiteTreeCount + $FileCount;

        // Re-index database
        $obj = new ZendSearchLuceneCMSDecorator();
        $obj->rebuildZendSearchLuceneIndex();

        // Has correct number of items?
        $this->assertEquals( $IndexableCount, ZendSearchLuceneWrapper::getIndex()->count() );
        
    }

}

