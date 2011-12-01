<?php

/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.error.exception');
require_once('abstractmediarelations.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'tables'.
DS.'categorymediarelations.php');

class Hub2ModelCategoryMediaRelations extends Hub2ModelAbstractMediaRelations {

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->_table = new Hub2TableCategoryMediaRelations($this->_db);
    }

    public function getRelatedMediaDetails($itemId,$size) {
        return $this->_table->getRelationDetails($itemId,$size);
    }

    /**
     * Delete category to media relations
     * @param $id int ID of the category whose relations are to be deleted
     * @return boolean true on success
     */
    public function deleteCategoryRelations($id) {
        return $this->_table->deleteRelations($id);
    }

}