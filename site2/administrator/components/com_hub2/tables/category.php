<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('hub2.php');
/**
 * Region Table
 * @author anurag
 *
 */
class Hub2TableCategory extends Hub2Table {
    /**
     * @var Int
     */
    var $id = null;
    /**
     * @var varchar
     */
    var $title = null;
    /**
     * @var varchar
     */
    var $alias = null;
    /**
     * @var varchar
     */
    var $subtitle = null;
    /**
     * @var varchar
     */
    var $body = null;
    /**
     * @var varchar
     */
    var $params = null;
    /**
     * @var varchar
     */
    var $media=null;

    /**
     * @var int
     */
    var $published = null;

    /**
     * @var int
     */
    var $ordering = null;

    /**
     * @var text
     */
    var $comment = null;

    /**
     * @var text
     */
    var $metakey = null;

    /**
     * @var text
     */
    var $metadesc = null;

    /**
     * @var Int
     */
    var $parent_id = null;

    /**
     * @var Int
     */
    var $level = null;

    /**
     * @var Int
     */
    var $lft = null;

    /**
     * @var Int
     */
    var $rgt = null;

    /**
     * @var Int
     */
    var $checked_out = null;

    /**
     * @var Datetime
     */
    var $checked_out_time = null;

    /**
     * @var Int
     */
    var $created_by = null;

    /**
     * @var Datetime
     */
    var $created = null;

    /**
     * @var Int
     */
    var $modified_by = null;

    /**
     * @var Datetime
     */
    var $modified = null;
    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__hub2_categories', 'id', $db );
    }

    /**
     * Loads a row from the database and binds the fields to the object properties
     *
     * @access  public
     * @param   mixed   Optional primary key.  If not specifed, the value of current key is used
     * @return  boolean True if successful
     */
    function load( $oid=null ) {
        $k = $this->_tbl_key;

        if ($oid !== null) {
            $this->$k = $oid;
        }

        $oid = $this->$k;

        if ($oid === null) {
            return false;
        }
        $this->reset();

        $db =& $this->getDBO();

        $query = 'SELECT * FROM '.$this->_tbl
        . ' WHERE '.$this->_tbl_key.' = '.$db->Quote($oid);
        $db->setQuery( $query );

        if ($result = $db->loadAssoc( )) {
            if ($result['media'] === null) {
                $result['media'] = '';
            }
            return $this->bind($result);
        }
        else
        {
            $this->setError( $db->getErrorMsg() );
            return false;
        }
    }


}
