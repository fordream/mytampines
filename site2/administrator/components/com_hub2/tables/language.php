<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.database.table');


/**
 * Language Table
 *
 */
class Hub2TableLanguage extends JTable {

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__languages', 'id', $db );
    }

    function getLanguages($active=true) {
        // check if table exists -> else only English
        try {
            $currentHandler = &JError::getErrorHandling(E_ERROR);
            // use the function myErrorHandler to deal with E_ERROR errors
            JError::setErrorHandling( E_ERROR,'Ignore');
            if ($active) {
                $this->_db->setQuery(
                'SELECT name,shortcode as cvalue from #__languages where active=1');
            } else {
                $this->_db->setQuery('SELECT name,shortcode as cvalue from #__languages');
            }
            $results = $this->_db->loadObjectList();
            if (!$results) {
                $results = $this->getDefault();
            }
            // restore error handling
            JError::setErrorHandling( E_ERROR,$currentHandler['mode'],$currentHandler['options']);

        } catch (Exception $e) {
            $results = $this->getDefault();
        }
        return $results;
    }

    private function getDefault() {
        $lang = &JFactory::getLanguage();
        $t = new JObject;
        $codes = split('-',$lang->getTag());
        $shortcode = $codes[0];
        $t->set('name',$lang->getName());
        $t->set('cvalue',$shortcode);
        $results = array();
        $results[] = $t;
        return $results;
    }
}
