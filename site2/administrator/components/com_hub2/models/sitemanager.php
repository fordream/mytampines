<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jximport( 'jxtended.database.query');
require_once('model.php');

class Hub2ModelSiteManager extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'sitemanager';
        parent::__construct($config);
    }

    /**
     * Returns true if the given values are validated for the item
     * @param $values
     * @param $errors
     * @return boolean
     */
    function validateData($values, &$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        $return = true;
        $this->_validator->loadAndSetFieldValidateRules('site.validation.rule');
        $return = $this->_validator->validate($item,$errors) && $return;
        return $return;
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $values['id']=$this->getState('id');
        $request        = $this->getState( 'request' );

        // save current primary key to pkey2 if site already exists
        if ($values['id'] !== 0) {
            $table = $this->getResource();
            $table->load($values['id']);
        }
        $result = $this->_dataModel->save($values, $this->getResource());
        return $result;
    }



    /**
     * delete an item - simply disables it.
     * Ensures a template cannot be disabled till there are sites associated with it
     * @return true or JError object
     */
    function delete($id) {
        // Cannot delete a site
    }

    function getDetails($ids) {
        return $this->_dataModel->getDetails($ids);
    }
}

?>