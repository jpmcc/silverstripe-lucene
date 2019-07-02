<?php

class ZendSearchLuceneSiteConfigTest extends SapphireTest {

    function testUpdateCMSActions() {
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
        
        ZendSearchLuceneSearchable::enable(array());

        $config = SiteConfig::current_site_config();        
        $this->assertTrue( is_object($config->getCMSActions()->fieldByName('rebuildZendSearchLuceneIndex')) );
    
    }

}

