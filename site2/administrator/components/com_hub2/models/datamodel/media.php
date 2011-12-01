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

class Hub2DataModelMedia extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_media';
        parent::__construct($config);
    }

    function getImages($folder_id) {
        $db=$this->_db;

        $db->setQuery(
            "select name, path,path_relative,thumbnail_relative,
            size,width,height,type,mime,view_publish_url,view_thumbnail_url
            from #__hub2_media where type=0 and resized_image=0 and folder_id=".$folder_id.
            " order by name");
        return $db->loadObjectList();

    }

    function getAttachments($folder_id) {
        $db=$this->_db;

        $db->setQuery(
            "select name, path,path_relative,thumbnail_relative,
            size,width,height,type,mime,view_publish_url,view_thumbnail_url
            from #__hub2_media where type=1 and folder_id=".
        $folder_id." order by name");
        return $db->loadObjectList();
    }



    function getResizedImages($media_id) {
        $db=$this->_db;
        $db->setQuery(
            "select * from #__hub2_media where resized_image=1 AND parent_media_id=".$media_id);
        return $db->loadAssocList("image_size_desc");
    }

    /**
     * filters implemented
     * folder_id
     * select
     * search_tag
     * search_filename
     * @param $filters
     * @param $resolveFKs
     */
    function _getListQuery( $filters, $resolveFKs = false ) {
        $db = $this->_db;

        $qb = new JXQuery;

        if (array_key_exists('select', $filters) &&  trim($filters['select']) !== '') {
            $qb->select($filters['select']);
        } else {
            $qb->select('t.*');
        }

        $qb->from('#__hub2_media t');

        // get images created by an user only
        if (array_key_exists('created_by', $filters) && trim($filters['created_by']) !== '') {
            $qb->where("created_by='".$filters['created_by']."'");
        }

        if (array_key_exists('search_filename', $filters) &&
                trim($filters['search_filename']) !== '') {
            $qb->where("name LIKE '%{$filters['search_filename']}%'");
        }

        if (array_key_exists('search_tag', $filters) &&
             (int)trim($filters['search_tag']) > 0) {
            $qb->join( 'LEFT', '#__hub2_media_tags_relations AS mtr ON mtr.media_id=t.id' );
            $qb->where("mtr.tag_id={$filters['search_tag']}");
        }
        // implement filters for search media related to a particular item
        // NOTE: (int)trim($filters['item_id']) >= 0 (equals as well so we do not return
        // entire media library when a new item is being created
        if ( array_key_exists('item_id', $filters) && array_key_exists('type_id', $filters) &&
        (int)trim($filters['item_id']) >= 0 && (int)trim($filters['type_id']) > 0) {
            $qb->join( 'LEFT', '#__hub2_item_media_relations AS imr ON imr.media_id=t.id' );
            $qb->where("imr.item_id={$filters['item_id']} AND imr.type_id={$filters['type_id']}");
            $qb->select("imr.size_description,imr.caption");
        }

        if (array_key_exists('folder_id', $filters) && is_array($filters['folder_id'])) {
            $str = implode(" OR folder_id=",$filters['folder_id']);
            $qb->where("(folder_id={$str})");
        } else if ( array_key_exists('folder_id', $filters) &&
            (int)trim($filters['folder_id']) > 0) {
            $qb->where("folder_id={$filters['folder_id']}");
        }

        if (array_key_exists('type', $filters) && trim($filters['type']) !== '') {
            $qb->where("type='".$filters['type']."'");
        }
        return $qb;
    }

    /**
     *
     * @param $id
     * @return array
     */
    function getTagsForObject($id) {
        $sql = "SELECT tag_id as id from #__hub2_media_tags_relations where media_id={$id}";
        $this->_db->setQuery($sql);
        return $this->_db->loadResultArray();
    }

    function replaceTagsForItem($id, $tagArray) {
        $this->_db->Execute("DELETE from #__hub2_media_tags_relations where media_id={$id}");
        foreach ($tagArray as $tag) {
            $this->_db->Execute("INSERT INTO #__hub2_media_tags_relations
            (media_id,tag_id) VALUES ({$id},{$tag})");
        }
    }

    /**
     * @param mediaIds array
     * @return array of Objects
     */
    function getDetails($mediaIds) {
        $sql = "SELECT * from #__hub2_media WHERE ";
        if (is_array($mediaIds)) {
            if (count($mediaIds) < 10) {
                $sql .= "(id=".implode(" OR id=",$mediaIds).")";
            } else {
                $sql .= "id IN (".implode(",",$mediaIds).")";
            }
        } else {
            $sql .= "id = " .$mediaIds;
        }
        $this->_db->setQuery($sql);
        return $this->_db->loadObjectList();
    }
}
