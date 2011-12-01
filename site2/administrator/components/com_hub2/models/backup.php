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

class Hub2ModelBackup extends Hub2Model {
    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'backup';
        parent::__construct($config);
    }

}