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
require_once('tree.php');

class Hub2DataModelFolder extends Hub2DataModelTree {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_media_folders';
        parent::__construct($config);
    }

    function getFolder($path_relative) {
        $db=$this->_db;
        $db->setQuery("select * from #__hub2_media_folders where path_relative=".
        $db->Quote($path_relative));
        return $db->loadObject();
    }

    function getMediaCount($id) {
        $db=$this->_db;
        $db->setQuery("select count(id) from #__hub2_media where folder_id=".$id);
        return $db->loadResult();
    }

    function addFolder($tableObject) {
        $result = $tableObject->store();
        if ($result) {
            // rebuild the tree
            $this->rebuild("hub2_media_folders");
            $result = $tableObject->id;
        } else {
            $result = JError::raiseWarning( 500, $tableObject->getError() );
        }
        return $result;
    }

    function getSubFolders($folder_id, $showhidden = false) {
        $db=$this->_db;
        $extra = " AND hidden_folder=0";
        if ($showhidden) {
            $extra = "";
        }
        $db->setQuery("select * from #__hub2_media_folders where parent_id=".
        $folder_id.$extra." order by name");
        return $db->loadObjectList();
    }

    function getRootFolderId() {
        $db=$this->_db;
        $db->setQuery("select id from #__hub2_media_folders where parent_id=0");
        return $db->loadResult();
    }

    function getFolderCount() {
        $db=$this->_db;
        $db->setQuery("select count(id) from #__hub2_media_folders");
        return $db->loadResult();
    }

    function getFolders($showhidden = false) {
        $filters['rootnode'] = 0; // this allows to get all root nodes and not one specific node
        $filters['show_hidden'] = false;
        if ($showhidden) {
            $filters['show_hidden'] = true;
        }
        $db = $this->_db;
        $qb = $this->_getListQuery($filters);
        $db->setQuery($qb->toString());
        return $db->loadObjectList();
    }

    function getPossibleParents($node) {
        $filters['rootnode'] = 0; // this allows to get all root nodes and not one specific node
        $filters['exclude_node_and_children'] = $node;
        $query = $this->_getListQuery($filters,$resolveFKs);
        $sql            = $query->toString();
        $result         = $this->_getList($sql, 0,0);
        return $result;
    }

    function canAddSubFolderWithName($name,$parent_id) {
        $db = $this->_db;
        $db->setQuery("select count(*) from #__hub2_media_folders where
                        parent_id={$parent_id} and name=".$db->Quote($name));
        $result = $db->loadResult();
        return (int)$result == 0;
    }
    /**
     * filters implemented
     * rootnode - id of the root node, if 0 then all root nodes
     * exclude_node_and_children - id to exclude
     * show_hidden - show hidden folders
     * @param $filters
     * @param $resolveFKs
     */
    function _getListQuery($filters,$resolveFKs = false) {
        $filters['table_name'] = 'hub2_media_folders';
        if (!array_key_exists('rootnode',$filters)) {
            $filters['rootnode'] = 0;
        }
        $qb = parent::_getListQuery($filters,$resolveFKs);

        if (array_key_exists('show_hidden',$filters)) {
            if ($filters['show_hidden'] === true) {
            } else {
                $qb->where('hidden_folder=0');
            }
        } else {
            $qb->where('hidden_folder=0');
        }
        return $qb;
    }

}