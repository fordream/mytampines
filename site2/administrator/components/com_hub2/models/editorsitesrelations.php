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
jximport( 'jxtended.database.query');
require_once('model.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.
'tables'.DS.'editorsitesrelations.php');

class Hub2ModelEditorSitesRelations extends Hub2Model {

    public function __construct($config=array()) {
        $this->_name = 'editorsitesrelations';
        parent::__construct($config);
        $table = new Hub2TableEditorSitesRelations($this->_db);
        $this->setResource($table);
    }

    public function getSites($itemId) {
        return $this->getResource()->getRelations($itemId);
    }

    public function getSiteIds($itemId) {
        $catIds = null;
        $rows = $this->getResource()->getRelations($itemId);
        foreach ($rows as $row) {
            $catIds[] = $row->site_id;
        }
        return $catIds;
    }

    public function getItem() {
        $state = $this->getState();
        $filters        = JArrayHelper::fromObject( $state );
        return $this->getResource()->getUser($filters['id']);
    }

    public function getEditorProfile() {
        $i = $this->getItem();
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
        DS.'tables'.DS.'editorprofile.php');
        $table = new JTableEditorProfile($this->_db);
        return $table->getEditorProfile($i->id);
    }

    function _getListQuery( $options, $resolveFKs=false) {
        return $this->getResource()->_getListQuery($options,$resolveFKs);
    }

    public function updateSites($itemId, $siteIds = array()) {
        if (is_array($siteIds) || is_int($siteIds)) {
            $result = $this->getResource()->deleteRelations($itemId);
            if (!$result) {
                $this->setError(JText::_('ERROR_DELETE_OLD'));
                return false;
            }
            if(!empty($siteIds)) {
                $result = $this->getResource()->addRelations($itemId, $siteIds);
                if (!$result) {
                    $this->setError($this->getResource()->getError());
                }
            }
            return $result;
        }
        $this->setError(JText::_('ERROR_UPDATE_RELATIONS'));
        return false;
    }

    /**
     * Returns true if the given values are validated for the item
     * @param $values
     * @param $errors
     * @return boolean
     */
    function validateData($values, &$errors) {
        if (!$this->getResource()->getUser($values['user_id'])) {
            $errors[] = "User does not exist";
            return false;
        }
        return true;
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $request        = $this->getState( 'request' );

        $params         = JArrayHelper::getValue( $request['jxform'], 'profile', array(), 'array' );
        if ($params) {
            $registry = new JRegistry();
            $registry->loadArray( $params );
            $values['profile'] = $registry->toString();
        }

        // handle the regions
        $sites        = JArrayHelper::getValue( $request, 'jxform_site', array(), 'array' );
        $values['site'] = $sites;

        // update the profile table
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
        DS.'tables'.DS.'editorprofile.php');
        $table = new JTableEditorProfile($this->_db);
        if (!$table->bind($values)) {
            $result =  JError::raiseNotice('ERROR_CODE',
            JText::_($table->getError()));
            return $result;
        }
        if (!$table->store()) {
            $result =  JError::raiseNotice('ERROR_CODE',
            JText::_($table->getError()));
            return $result;
        }

        $result = $this->updateSites($values['user_id'],$values['site']);

        if (!$result) {
            $result =  JError::raiseNotice('ERROR_CODE',
            JText::_($this->getError()));
        }
        return $result;
    }

    function delete($id) {
        $result = $this->getResource()->deleteRelations($id);
        if (!$result) {
            $result = JError::raiseNotice('ERROR_CODE', JText::_('ERROR_DELETE_OLD'));
            return $result;
        }
        return $result;
    }

    function canPropagateToSite($userId,$siteId) {
        return $this->getResource()->checkRelationExists($userId,$siteId);
    }

    function canDeleteFromSite($userId,$siteId) {
        return $this->getResource()->checkRelationExists($userId,$siteId);
    }

}