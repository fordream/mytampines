<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
require_once('model.php');

class Hub2ModelPoll extends Hub2Model {

    function __construct($config=array()) {
        $this->_name = 'poll';
        parent::__construct($config);
    }

    public function getList() {
        return $this->_dataModel->getList();
    }
}

?>