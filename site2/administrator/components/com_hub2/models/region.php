<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jximport( 'jxtended.database.query' );
require_once('model.php');

/**
 * Region model
 *
 */
class Hub2ModelRegion extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'region';
        parent::__construct($config);
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $values['id']=$this->getState('id');

        $result = $this->_dataModel->save($values, $this->getResource());
        return $result;
    }

    function canDelete($id) {
        $count = $this->_dataModel->getNumberOfSites($id);
        $count1 = count($this->_dataModel->getChildIDs($id));
        return ($count == 0 && $count1 == 0);
    }

    function delete($id) {
        $count = $this->_dataModel->getNumberOfSites($id);
        if ($count > 0) {
            $result = JError::raiseWarning(500,"Cannot delete group with ID ".
            $id." since it has sites associated with it.");
            return $result;
        }
        $count1 = count($this->_dataModel->getChildIDs($id));
        if ($count1 > 0) {
            $result = JError::raiseWarning(500,"Cannot delete group with ID ".
            $id." since it has sub-groups.");
            return $result;
        }
        return $this->_dataModel->delete($id);
    }

    function validateData($values,&$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        // mapping of required fields;
        $this->_validator->loadAndSetFieldValidateRules('region.validation.rule');
        $return = $this->_validator->validate($item,$errors);
        $db = &JFactory::getDBO();
        $constraints = array();
        $constraints[] = 'parent_id='.$db->Quote($values['parent_id']);
        $constraints[] = 'name='.$db->Quote($values['name']);
        $id = $this->getState('id',0);
        if ($id) {
            $constraints[] = 'id<>'.$id;
        }
        if ($this->_dataModel->getCountForConstraint($constraints)) {
            $errors[] = JText::_('Region with same name already exists.');
            $return = false;
        }
        return $return;
    }

    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
    }

}