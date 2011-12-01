<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 * @author      joseph
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
require_once('model.php');
class Hub2ModelBusinesslisting extends Hub2Model {

    function __construct($config = array()) {
        $this->_name = 'Businesslisting';
        parent::__construct($config);
    }

    function updatecreatedby($state=null) {
        $db = &JFactory::getDBO();
        extract($state);
        echo $item_id;
        $exe = 'UPDATE #__hub2_businesslistings SET created_by='.$user_id.
            ' WHERE head_id='.$head_id;
        $db->Execute( $exe );
        return true;
    }

}