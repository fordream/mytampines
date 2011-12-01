<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * Base class for a Data Model
 *
 * Acts as a Factory class for application specific objects and
 * provides many supporting API functions.
 *
 */
class Hub2DataModel extends JObject {
    /**
     * The model (base) name
     *
     * @var string
     * @access  protected
     */
    var $_name;

    /**
     * Database Connector
     *
     * @var object
     * @access  protected
     */
    var $_db;

    /**
     * The table name
     *
     * @var string
     * @access  protected
     */
    var $_table;

    /**
     * Constructor
     *
     * @since   1.5
     */
    function __construct($config = array()) {
        //set the model state
        if (array_key_exists('state', $config))  {
            $this->_state = $config['state'];
        } else {
            $this->_state = new JObject();
        }

        //set the model dbo
        if (array_key_exists('dbo', $config))  {
            $this->_db = $config['dbo'];
        } else {
            $this->_db = &JFactory::getDBO();
        }
    }

    function getId($name) {
        return null;
    }

    /**
     * Returns a reference to the a Model object, always creating it
     *
     * @param   string  The model type to instantiate
     * @param   string  Prefix for the model class name. Optional.
     * @param   array   Configuration array for model. Optional.
     * @return  mixed   A model object, or false on failure
     * @since   1.5
     */
    function &getInstance( $type, $prefix = '', $config = array() ) {
        $type       = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
        if ( empty( $prefix ) ) {
            $prefix = 'Hub2DataModel';
        }
        $modelClass = $prefix.ucfirst($type);
        $result     = false;

        $path = dirname( __FILE__ ) .DS .
        Hub2DataModel::_createFileName( 'model', array( 'name' => $type));
        require_once $path;
        if (!class_exists( $modelClass)) {
            JError::raiseWarning( 0, 'DataModel class ' . $modelClass .
                     ' not found in file.' );
            return $result;
        }

        $result = new $modelClass($config);
        return $result;
    }

    /**
     * Method to get the database connector object
     *
     * @access  public
     * @return  object JDatabase connector object
     * @since   1.5
     */
    function &getDBO() {
        return $this->_db;
    }

    /**
     * Method to set the database connector object
     *
     * @param   object  $db A JDatabase based object
     * @return  void
     * @since   1.5
     */
    function setDBO(&$db) {
        $this->_db =& $db;
    }

    /**
     * Method to get the model name
     * The model name by default parsed using the classname, or it can be set
     * by passing a $config['name�] in the class constructor
     *
     * @access  public
     * @return  string The name of the model
     * @since   1.5
     */
    function getName() {
        $name = $this->_name;

        if (empty( $name )) {
            $r = null;
            if (!preg_match('/Model(.*)/i', get_class($this), $r)) {
                JError::raiseError (500,
                    "Hub2DataModel::getName() : Can't get or parse class name.");
            }
            $name = strtolower( $r[1] );
        }

        return $name;
    }


    /**
     * Add a directory where Hub2DataModel should search for models. You may
     * either pass a string or an array of directories.
     *
     * @access  public
     * @param   string  A path to search.
     * @return  array   An array with directory elements
     * @since   1.5
     */
    function addIncludePath( $path='' ) {
        static $paths;

        if (!isset($paths)) {
            $paths = array();
        }
        if (!empty( $path ) && !in_array( $path, $paths )) {
            jimport('joomla.filesystem.path');
            array_unshift($paths, JPath::clean( $path ));
        }
        return $paths;
    }

    /**
     * Returns ID of table object that matches the given constraints provided
     * in an array as key value pairs
     * @param $constraints array key=>value pairs of constraints
     */
    public function getCountForConstraint($constraints) {
        $sql = "SELECT count(*) from ".$this->_table;
        if (count($constraints) > 0) {
            $sql .= " WHERE ".implode(" AND ",$constraints);
        }
        $this->_db->setQuery($sql);
        return $this->_db->loadResult();
    }

    /**
     * Returns an object list
     *
     * @param   string The query
     * @param   int Offset
     * @param   int The number of records
     * @return  array
     * @access  protected
     * @since   1.5
     */
    function &_getList( $query, $limitstart=0, $limit=0 ) {
        $this->_db->setQuery( $query, $limitstart, $limit );
        $result = $this->_db->loadObjectList();

        return $result;
    }

    /**
     * Returns a record count for the query
     *
     * @param   string The query
     * @return  int
     * @access  protected
     */
    function _getListCount( $query ) {
        $this->_db->setQuery( $query );
        $this->_db->query();

        return $this->_db->getNumRows();
    }

    /**
     * Create the filename for a resource
     *
     * @access  private
     * @param   string  $type  The resource type to create the filename for
     * @param   array   $parts An associative array of filename information
     * @return  string The filename
     * @since   1.5
     */
    function _createFileName($type, $parts = array()) {
        $filename = '';

        switch($type) {
            case 'model':
                $filename = strtolower($parts['name']).'.php';
                break;

        }
        return $filename;
    }
}