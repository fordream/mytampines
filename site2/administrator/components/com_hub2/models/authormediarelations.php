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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'tables'.DS.
'authormediarelations.php');

class Hub2ModelAuthorMediaRelations extends Hub2ModelAbstractMediaRelations {

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->_table = new Hub2TableAuthorMediaRelations($this->_db);
    }

    /**
     * Delete author to media relations
     * @return boolean true on success
     */
    public function deleteAuthorRelations($id) {
        return $this->_table->deleteRelations($id);
    }
}