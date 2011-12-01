<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'hub2includepaths.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'pagination.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.
DS.'datamodel'.DS.'datamodel.php');
/*
 function ob_postprocess($buffer){
 error_log($buffer);
 }
 */
/**
 * Hub2 abstract model
 *
 */
class Hub2Model extends JModel {

    var $_dataModel = null;

    /**
     * A validator object
     *
     * @var object
     * @access  protected
     */
    var $_validator;

    var $_name;

    /**
     * @var int The count of items returned by the model
     */
    var $_total = 0;

    var $_resource;

    function &getResource() {
        if ($this->_resource == null) {
            $this->_resource = $this->getTable($this->_name,'Hub2Table');
        }
        return $this->_resource;
    }

    /**
     * Set the internal resource. Adopted for JXModel
     * @param   JTable  The table object
     * @return  JTable  The previous value of the property
     */
    function &setResource( &$value ) {
        $oldValue = &$this->_resource;
        $this->_resource = $value;
        return $oldValue;
    }

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        if (empty($this->_name)) {
            JError::raiseError('500','Call to Hub2Model creation without a name set');
        }
        $name = $this->_name;
        /*
         static $instance = array();
         ob_start('ob_postprocess');
         if (array_key_exists($this->_name,$instance)) {
         $instance[$this->_name] = $instance[$this->_name] + 1;
         } else {
         $instance[$this->_name] = 1;
         }
         echo "creating model ". $this->_name . ' instance='.$instance[$this->_name]."\n";
         $trace = debug_backtrace(true);
         echo "from file ".$trace[1]['file']." at line ".$trace[1]['line']."\n";
         echo "from file ".$trace[2]['file']." at line ".$trace[2]['line']."\n";
         ob_end_flush();
         */
        $this->_dataModel = Hub2DataModel::getInstance($name);
        parent::__construct($config);

        // setup validator
        if (!array_key_exists('validator', $config))  {
            $validationPath = dirname(__FILE__).DS.'datamodel'.DS.'validators';
            /* commented since there is only the generic validator
             $path = $validationPath. DS .
             Hub2DataModel::_createFileName( 'model', array( 'name' => $name));
             $vmodelClass = "Hub2DataModelValidator".ucfirst($name);
             jimport('joomla.filesystem.file');
             if (!JFile::exists($path)) {
             */
            $path = $validationPath . DS.
            Hub2DataModel::_createFileName( 'model', array( 'name' => 'generic'));
            $vmodelClass = "Hub2DataModelValidator".ucfirst('generic');
            /*
             }*/
            require_once $path;
            $this->_validator = new $vmodelClass();
        }
    }

    /**
     * @return  JTable
     */
    function &getItem() {
        static $instances = array();

        $id     = $this->getState('id',0);
        if (!array_key_exists($id,$instances)) {
            $table  = $this->getTable($this->_name,'Hub2Table');
            if ($id !== 0) {
                $table->load($id);
            } else {
                $table->id = 0;
            }
            $instances[$id]   = $table;
        }
        return $instances[$id];
    }

    function getId($name) {
        return $this->_dataModel->getId($name);
    }
    /**
     *
     * @return array Objects
     */
    function &getItems($resolveFks = false) {

        $filters        = JArrayHelper::fromObject( $this->getState() );
        $limit      = @$filters['limit'];
        $limitstart = @$filters['limitstart'];

        $qb          = $this->_getListQuery( $filters, $resolveFks);
        $sql            = $qb->toString();
        $items         = $this->_getList( $sql, $limitstart,$limit);

        // set the total so that getPagination works
        $this->_total   = $this->_getListCount( $sql );

        return $items;
    }

    /**
     * Gets the Form
     * @param $name string naame of the form to get, leave empty to get the
     * same as the model
     * @return JXForm
     */
    function &getForm($name=null) {
        jximport( 'jxtended.form.helper' );
        JXFormHelper::addIncludePath( dirname(__FILE__));
        if ($name) {
            $result = &JXFormHelper::getView( $name );
        } else {
            $result = &JXFormHelper::getView( $this->_name );
        }
        return $result;
    }

    /**
     * Returns a JXQuery
     * @param $options array of filters - supported keys -
     * @param $resolveFKs boolean
     * @return JXQuery
     */
    function _getListQuery( $options, $resolveFKs=false) {
        return $this->_dataModel->_getListQuery($options,$resolveFKs);
    }

    function &getPagination() {
        $state = &$this->getState();
        $pagination = new Hub2Pagination( $this->_total, $state->get( 'limitstart'),
        $state->get( 'limit' ) );
        return $pagination;
    }

    // function adapted from JXModel
    function checkout() {
        $result = true;
        $id = (int)$this->getState( 'id' );
        if ($id > 0 ) {
            $user   = &JFactory::getUser();
            $userId = $user->get( 'id' );
            $table  = &$this->getTable($this->_name,'Hub2Table');
            if (property_exists( $table, 'checked_out' )) {
                $table->load( $id );
                if ($table->isCheckedOut( $userId, $table->checked_out )) {
                    $result = &JError::raiseNotice( 500, JText::_('Error: Item Checked Out') );
                } else {
                    $table->checkout( $userId );
                }
            }
        }
        return $result;
    }

    // function adapted from JXModel
    function checkin() {
        $result = true;
        $id = (int)$this->getState( 'id' );
        if ($id > 0 ) {
            $table  = &$this->getTable($this->_name,'Hub2Table');
            if (!$table->checkin($id)) {
                $result = new JException( $table->getError() );
            }
        }
        return $result;
    }

    /**
     * Check if can delete an item fiven by the ID
     * @param $id int ID of the item to delete
     * @return boolean true if the item can be deleted
     */
    function canDelete($id) {
        return true; // should be overriden by the implementing class
    }

    /**
     * clean the values before save (e.g, trim some values
     */
    function cleanData(&$values) {

    }
    /**
     * @return Hub2Table
     */
    function getItemById($id,$table=null,$useStatic=false) {
        static $items = array();
        if ($table == null) {
            if ($useStatic) {
                if (!array_key_exists($id,$items)) {
                    $table  = &$this->getTable($this->_name,'Hub2Table');
                    $items[$id] = $table;
                } else {
                    return  $items[$id];
                }
            } else {
                // hub-93: this creates new instance of table object to return
                $table  = &$this->getTable($this->_name,'Hub2Table');
            }
        }
        if ($id !== 0) {
            $table->load($id);
        }
        return $table;
    }

    // For testing only
    function setDataModel($model) {
        $this->_dataModel = $model;
    }

    function &getTable($name='', $prefix='Table', $options = array()) {
        if (strcasecmp($prefix,'Hub2Table') == 0) {
            // Clean the model name
            $name   = preg_replace( '/[^A-Z0-9_]/i', '', $name );
            $fileName = strtolower($name).'.php';
            $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'tables'.DS;
            if (file_exists($path.DS.$fileName)) {
                require_once ($path.DS.$fileName);
                $className =  'Hub2Table'.ucfirst($name);
                $table = new $className($this->_db);
                return $table;
            }
            $path = JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'tables'.DS;
            if (file_exists($path.DS.$fileName)) {
                require_once ($path.DS.$fileName);
                $className =  'Hub2Table'.ucfirst($name);
                $table = new $className($this->_db);
                return $table;
            }
        }
        return parent::getTable($name,$prefix,$options);
    }

    /**
     * Cleans text of all formating and scripting code
     */
    function cleanText ( $text ) {
        $text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace( '/<a\s+.*?href="([^"]*)"[^>]*>([^<]+)<\/a>/is', '', $text );
        $text = preg_replace( '/<!--.+?-->/', '', $text );
        $text = preg_replace( '/{.+?}/', '', $text );
        $text = preg_replace( '/&nbsp;/', ' ', $text );
        $text = preg_replace( '/&amp;/', ' ', $text );
        $text = preg_replace( '/&quot;/', ' ', $text );
        // TODO a better HTML strip $text = strip_tags( $text );
        return $text;
    }

    /**
     * Cleans URL of all formating and scripting code
     */
    function cleanURI( $text ) {
        $text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace( '/<a\s+.*?href="([^"]*)"[^>]*>([^<]+)<\/a>/is', '', $text );
        $text = preg_replace( '/<!--.+?-->/', '', $text );
        $text = preg_replace( '/{.+?}/', '', $text );
        $text = preg_replace( '/&nbsp;/', ' ', $text );
        $text = preg_replace( '/&amp;/', ' ', $text );
        $text = preg_replace( '/&quot;/', ' ', $text );
        $uri = JURI::getInstance($text);
        $uri = $uri->toString();
        $uri = preg_replace('#[\/]+$#','',$uri); // remove trailing slashes
        return $uri;
    }

    /**
     * Override getInstance to look into hub2 folders first
     * @param $type
     * @param $prefix
     * @param $config
     */
    function &getInstance( $type, $prefix = '', $config = array() ) {
        $type       = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
        $modelClass = $prefix.ucfirst($type);
        $result     = false;

        if (!class_exists( $modelClass )) {
            $fileName =   JModel::_createFileName( 'model', array( 'name' => $type));
            $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS;
            if (file_exists($path.DS.$fileName)) {
                require_once ($path.DS.$fileName);
                if (class_exists($modelClass)) {
                    $result = new $modelClass($config);
                    return $result;
                }
            }
            $path = JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'models'.DS;
            if (file_exists($path.DS.$fileName)) {
                require_once ($path.DS.$fileName);
                if (class_exists($modelClass)) {
                    $result = new $modelClass($config);
                    return $result;
                }
            }
        }
        return parent::getInstance( $type, $prefix, $config);
    }
}