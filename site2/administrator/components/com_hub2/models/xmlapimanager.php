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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.
'site.php');

/**
 * Region model
 *
 */
class Hub2ModelXmlapimanager extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'xmlapimanager';
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

    function delete($id) {
        return $this->_dataModel->remove($id);
    }

    function validateData($values,&$errors) {
        $item = $this->getItem();
        $return = $this->_validator->validate($item,$errors);
        if($values['exp_date'] <= date('Y-m-d')){
            $errors[] = JText::_("Expiry date must be more than today");
            $return = false;
        }
        return $return;
    }

    function validateAPI($key=null, $date=null) {
        return $this->_dataModel->validateAPI($key, date('Y-m-d'));
    }

}