<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jximport( 'jxtended.database.query');

class Hub2DataModelEditorsitesrelations extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_editor_sites_relations';
        parent::__construct($config);
    }
    /**
     * Get all item (editors) mapping for a given site id
     * @return list return all rows with given site id
     */
    public function getItems() {
        $state = $this->getState();
        $filters        = JArrayHelper::fromObject( $state );
        $query          = $this->_getListQuery( $filters);
        $sql            = $query->toString();
        $this->_total   = $this->_getListCount( $sql ); // need to set this for pagination to work
        $result         = $this->_getList( $sql, $state->get( 'limitstart' ),
        $state->get( 'limit' ));
        return $result;
    }

    /**
     * Gets the Form
     * @return JXForm
     */
    function &getForm( $type = 'view' ) {
        jximport( 'jxtended.form.helper' );
        JXFormHelper::addIncludePath( dirname(__FILE__));
        if ($type == 'model') {
            $result = &JXFormHelper::getModel( 'editorsitesrelations' );
        } else {
            $result = &JXFormHelper::getView( 'editorsitesrelations' );
        }
        if (JError::isError( $result )) {
            echo $result->message;
        }
        return $result;
    }
}
