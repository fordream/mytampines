<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'tables'.DS.'language.php');
/**
 * Language model
 *
 */
class Hub2ModelLanguage extends JModel {

    /**
     * Returns a list of languages available
     * @param $active boolean true to return active languages only
     * @return a JTable object list
     */
    public function getLanguages($active = true) {
        $table = new Hub2TableLanguage($this->_db);
        return $table->getLanguages($active);
    }
}