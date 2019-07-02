<?php

/**
 * The search form.
 *
 * @package lucene-silverstripe-module
 * @author Darren Inwood <darren.inwood@chrometoaster.com>
 */
class ZendSearchLuceneForm extends Form {
    
    public function __construct($controller) {
        $searchText = isset($_REQUEST['Search']) ? $_REQUEST['Search'] : '';
        $fields = FieldList::create(
            TextField::create('Search')->setTitle($searchText)
        );
        $actions = FieldList::create(
            FormAction::create('ZendSearchLuceneResults', _t('SearchForm.GO', 'Go'))
        );
        parent::__construct($controller, 'ZendSearchLuceneForm', $fields, $actions);
        $this->disableSecurityToken();
        $this->setFormMethod('get');
    }
    
    public function forTemplate() {
        return $this->renderWith([
            'ZendSearchLuceneForm',
            'Form'
        ]);
    }
    
}

