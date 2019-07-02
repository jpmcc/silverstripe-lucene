<?php

// Named strangely so it runs first - Object::remove_extension doesn't seem to work

class A00_ZendSearchLuceneSearchableTest extends SapphireTest {

    static $fixture_file = 'ZendSearchLuceneSearchableTest.yml';

    public function testEnable() {
        // test for baddies in _config.php
        if ( ContentController::has_extension('ZendSearchLuceneContentController') ) {
            echo '<p>Please remove calls to ZendSearchLuceneSearchable::enable() from your _config.php file before running tests.</p>';
            die();
        }

        // Setup
        ContentController::remove_extension('ZendSearchLuceneContentController');
        SiteConfig::remove_extension('ZendSearchLuceneSiteConfig');
        LeftAndMain::remove_extension('ZendSearchLuceneCMSDecorator');
        SiteTree::remove_extension('ZendSearchLuceneSearchable');
        File::remove_extension('ZendSearchLuceneSearchable');

        // Are we fresh?
        $this->assertFalse( ContentController::has_extension('ZendSearchLuceneContentController') );
		$this->assertFalse( SiteConfig::has_extension('ZendSearchLuceneSiteConfig') );
		$this->assertFalse( LeftAndMain::has_extension('ZendSearchLuceneCMSDecorator') );
        $this->assertFalse( SiteTree::has_extension('ZendSearchLuceneSearchable') );
        $this->assertFalse( File::has_extension('ZendSearchLuceneSearchable') );

        ZendSearchLuceneSearchable::$pageLength = 10;
        ZendSearchLuceneSearchable::$alwaysShowPages = 3;   
        ZendSearchLuceneSearchable::$maxShowPages = 8;   
        ZendSearchLuceneSearchable::$encoding = 'utf-8';
        ZendSearchLuceneSearchable::$cacheDirectory = TEMP_FOLDER;
        ZendSearchLuceneWrapper::$indexName = 'Test';

        ZendSearchLuceneSearchable::enable(array());
        $this->assertTrue( ContentController::has_extension('ZendSearchLuceneContentController') );
		$this->assertTrue( SiteConfig::has_extension('ZendSearchLuceneSiteConfig') );
		$this->assertTrue( LeftAndMain::has_extension('ZendSearchLuceneCMSDecorator') );
        $this->assertFalse( SiteTree::has_extension('ZendSearchLuceneSearchable') );
        $this->assertFalse( File::has_extension('ZendSearchLuceneSearchable') );

        ZendSearchLuceneSearchable::enable(array('File'));
        $this->assertFalse( SiteTree::has_extension('ZendSearchLuceneSearchable') );
        $this->assertTrue( File::has_extension('ZendSearchLuceneSearchable') );

        ZendSearchLuceneSearchable::enable(array('File','SiteTree'));
        $this->assertTrue( SiteTree::has_extension('ZendSearchLuceneSearchable') );
        $this->assertTrue( File::has_extension('ZendSearchLuceneSearchable') );

    }

    public function testGetSearchedVars() {
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
                
        $sitetree = DataObject::get_one('Page');
        $this->assertEquals( 
            $sitetree->getSearchedVars(), 
            array(
                0 => 'ID',
                1 => 'ClassName',
                2 => 'LastEdited',
                3 => 'Title',
                4 => 'MenuTitle',
                5 => 'Content',
                6 => 'MetaTitle',
                7 => 'MetaDescription',
                8 => 'MetaKeywords',
                9 => 'Link'
            )
        );

        $file = DataObject::get_one('File');
        $this->assertEquals( 
            $file->getSearchedVars(),
            array(
                0 => 'ID',
                1 => 'ClassName',
                2 => 'LastEdited',
                3 => 'Filename',
                4 => 'Title',
                5 => 'Content',
                6 => 'Link'
            )
        );

    }

    public function testGetSearchFields() {
        // Setup
        SiteTree::remove_extension('ZendSearchLuceneSearchable');
        File::remove_extension('ZendSearchLuceneSearchable');
        
        ZendSearchLuceneSearchable::$pageLength = 10;
        ZendSearchLuceneSearchable::$alwaysShowPages = 3;   
        ZendSearchLuceneSearchable::$maxShowPages = 8;   
        ZendSearchLuceneSearchable::$encoding = 'utf-8';
        ZendSearchLuceneSearchable::$cacheDirectory = TEMP_FOLDER;
        ZendSearchLuceneWrapper::$indexName = 'Test';
        
        ZendSearchLuceneSearchable::enable();
                
        $sitetree = DataObject::get_one('Page');
        $this->assertEquals( 
            $sitetree->getSearchFields(), 
            'Title,MenuTitle,Content,MetaTitle,MetaDescription,MetaKeywords'
        );

        $file = DataObject::get_one('File');
        $this->assertEquals( 
            $file->getSearchFields(),
            'Filename,Title,Content'
        );    
    }

    public function testGetExtraSearchFields() {
        // Setup
        SiteTree::remove_extension('ZendSearchLuceneSearchable');
        File::remove_extension('ZendSearchLuceneSearchable');
        
        ZendSearchLuceneSearchable::$pageLength = 10;
        ZendSearchLuceneSearchable::$alwaysShowPages = 3;   
        ZendSearchLuceneSearchable::$maxShowPages = 8;   
        ZendSearchLuceneSearchable::$encoding = 'utf-8';
        ZendSearchLuceneSearchable::$cacheDirectory = TEMP_FOLDER;
        ZendSearchLuceneWrapper::$indexName = 'Test';
        
        ZendSearchLuceneSearchable::enable();
                
        $sitetree = DataObject::get_one('Page');
        $this->assertEquals( 
            $sitetree->getExtraSearchFields(), 
            array('ID', 'ClassName', 'LastEdited')
        );

        $file = DataObject::get_one('File');
        $this->assertEquals( 
            $file->getExtraSearchFields(),
            array('ID', 'ClassName', 'LastEdited')
        );
    }

    public function testOnAfterWrite() {
        // Setup
        SiteTree::remove_extension('ZendSearchLuceneSearchable');
        File::remove_extension('ZendSearchLuceneSearchable');
        
        ZendSearchLuceneSearchable::$pageLength = 10;
        ZendSearchLuceneSearchable::$alwaysShowPages = 3;   
        ZendSearchLuceneSearchable::$maxShowPages = 8;   
        ZendSearchLuceneSearchable::$encoding = 'utf-8';
        ZendSearchLuceneSearchable::$cacheDirectory = TEMP_FOLDER;
        ZendSearchLuceneWrapper::$indexName = 'Test';
        
        ZendSearchLuceneSearchable::enable();
    
        // Blank the index
        ZendSearchLuceneWrapper::getIndex(true);

        // There shouldn't be anything with asdf in there
        $this->assertEquals( 0, count(ZendSearchLuceneWrapper::find('asdf')) );

        $page = DataObject::get_one('Page');
        $page->Content = 'asdf';
        $page->write();

        // There should now be a result
        $this->assertGreaterThan( 0, count(ZendSearchLuceneWrapper::find('asdf')) );

        $page->Content = 'foo bar';
        $page->write();

        // There should now be no result again
        $this->assertEquals( 0, count(ZendSearchLuceneWrapper::find('asdf')) );
    }

    public function testOnAfterDelete() {
        // Setup
        SiteTree::remove_extension('ZendSearchLuceneSearchable');
        File::remove_extension('ZendSearchLuceneSearchable');
        
        ZendSearchLuceneSearchable::$pageLength = 10;
        ZendSearchLuceneSearchable::$alwaysShowPages = 3;   
        ZendSearchLuceneSearchable::$maxShowPages = 8;   
        ZendSearchLuceneSearchable::$encoding = 'utf-8';
        ZendSearchLuceneSearchable::$cacheDirectory = TEMP_FOLDER;
        ZendSearchLuceneWrapper::$indexName = 'Test';
        
        ZendSearchLuceneSearchable::enable();
    
        // Blank the index
        ZendSearchLuceneWrapper::getIndex(true);

        // There shouldn't be anything with asdf in there
        $this->assertEquals( 0, count(ZendSearchLuceneWrapper::find('asdf')) );

        $page = DataObject::get_one('Page');
        $page->Content = 'asdf';
        $page->write();

        // There should now be a result
        $this->assertGreaterThan( 0, count(ZendSearchLuceneWrapper::find('asdf')) );

        $page->delete();

        // There should now be no result again
        $this->assertEquals( 0, count(ZendSearchLuceneWrapper::find('asdf')) );
    }

}

