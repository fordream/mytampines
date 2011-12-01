<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.utilities.date');

/**
 * Extends JTable
 *
 */
class Hub2Table extends JTable {


    /**
     * custom save method
     */
    function store($updatenulls=false) {
        $jApp = &JFactory::getApplication();

        $user = & JFactory::getUser();
        $conf =& JFactory::getConfig();
        //$txOffset   = $conf->get('offset');
        $now        = new JDate();

        $vars = $this->getProperties(); // get_object_vars($this);
        $k = $this->_tbl_key;

        if(!$this->$k) {
            if (array_key_exists( 'created', $vars)) {
                $this->created = $now->toMySQL();
            }
            if (array_key_exists( 'created_by', $vars)  && empty($vars['created_by'])) {
                // only set if not previosuly set
                // else we loose the 1st author of the object
                $this->created_by = $user->get('id');
            }
            if (array_key_exists( 'modified', $vars)) {
                $this->modified = $now->toMySQL();
            }
            if (array_key_exists( 'modified_by', $vars)) {
                $this->modified_by = $user->get('id');
            }
        } else {
            if (array_key_exists( 'modified', $vars)) {
                $this->modified = $now->toMySQL();
            }
            if (array_key_exists( 'modified_by', $vars)) {
                $this->modified_by = $user->get('id');
            }
        }

        return parent::store($updatenulls);
    }

    /**
     * @deprecated this method should not be used
     */
    public function getReplaceSQLForPropagation() {
        //$fmtsql = 'REPLACE INTO '.$this->_db->nameQuote($this->_tbl).' (%s) VALUES (%s)';
        $fmtsql = ' (%s) VALUES (%s)';
        $tmp = array();
        $vars = $this->getProperties(); // get_object_vars($this);
        foreach ($vars as $k => $v) {
            if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                continue;
            }
            if ($v === null) {
                $val = 'NULL';
            } else {
                $val = $this->_db->isQuoted( $k ) ? $this->_db->Quote( $v ) : (int)$v;
            }
            $field[]=$this->_db->nameQuote( $k );
            $values[] = $val;
        }
        return sprintf( $fmtsql, implode( ",", $field ) , implode( ",", $values ) );
    }

    public function getWhereClauseForDelete($id) {
        // only need the where clause
        $fmtsql = $this->_db->nameQuote($this->_tbl_key).' = '.$this->_db->Quote($id);
        return $fmtsql;
    }

    /**
     * Overrides getInstance to look in Hub2 folders first
     * Returns a reference to the a Table object, always creating it
     *
     * @param type      $type    The table type to instantiate
     * @param string    $prefix  A prefix for the table class name. Optional.
     * @param array     $options Configuration array for model. Optional.
     * @return database A database object
     * @since 1.5
     */
    function &getInstance( $type, $prefix = 'JTable', $config = array() ) {
        $false = false;

        $type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
        $tableClass = $prefix.ucfirst($type);

        if (!class_exists( $tableClass )) {
            $fileName = strtolower($type).'.php';
            $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'tables'.DS;
            if (file_exists($path.DS.$fileName)) {
                require_once ($path.DS.$fileName);
            } else {
                $path = JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'tables'.DS;
                if (file_exists($path.DS.$fileName)) {
                    require_once ($path.DS.$fileName);
                }
            }
        }
        return parent::getInstance($type,$prefix,$config);
    }
}

