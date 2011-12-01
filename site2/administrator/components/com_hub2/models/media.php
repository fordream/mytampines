<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jximport( 'jxtended.application.component.model' );
jximport( 'jxtended.database.query' );
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
require_once(dirname(__FILE__).DS.'..'.DS.'..'.DS.'com_media'.DS.'helpers'.DS.'media.php');
require_once(dirname(__FILE__).DS.'..'.DS.'helpers'.DS.'pagination.php');
require_once('model.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'mediaHelper.php');

/**
 * Media Model
 *
 */
class Hub2ModelMedia extends Hub2Model {


    var $_pagination;

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'media';
        parent::__construct($config);
    }

    /**
     * list of tag ids for a media object
     * @param $id
     */
    function getTagIDsForMedia($id) {
        return $this->_dataModel->getTagsForObject($id);
    }

    function getMediaObject($id) {
        $table = $this->getResource();
        $table->load($id);
        return $table;
    }

    function getMedia($folder_id, $type = '') {
        $state          = &$this->getState();
        $filters        = JArrayHelper::fromObject( $state );

        if ($type !== '') {
            $filters['type'] = $type;
        }
        // check filter['include_subfolders']
        if ($filters['include_subfolders'] == 1) {
            // need to get the subfolders of the given folder id
            $folderdataModel = Hub2DataModel::getInstance('folder');
            $subfolders = $folderdataModel->getSubFolders($folder_id);
            if ($subfolders) {
                $folders[] = $folder_id;
                foreach ($subfolders as $subfolder) {
                    $folders[] = $subfolder->id;
                }
                $filters['folder_id'] = $folders;
            } else {
                $filters['folder_id'] = $folder_id;
            }
        } else {
            $filters['folder_id'] = $folder_id;
        }
        $query          = $this->_dataModel->_getListQuery($filters);
        $sql            = $query->toString();
        $result         = $this->_getList($sql, $state->get('limitstart'), $state->get('limit'));
        $this->_total   = $this->_getListCount( $sql ); // required for pagination to work
        return $result;
    }

    function getMediaForItem($item_id, $type_id) {
        $filters['item_id'] = $item_id;
        $filters['type_id'] = $type_id;
        // check filter['include_subfolders']
        $query          = $this->_dataModel->_getListQuery($filters,null);
        $sql            = $query->toString();
        $result         = $this->_getList($sql);
        return $result;
    }

    function validateData($values,&$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        // mapping of required fields;
        $this->_validator->loadAndSetFieldValidateRules('media.validation.rule');
        return $this->_validator->validate($item,$errors);
    }

    function &getForm() {
        JXFormHelper::addIncludePath( JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'models' );
        return parent::getForm();
    }

    /**
     * user_uploaded is optional, thumbnail_relative is optional for videos
     * base is the base path from root of the site
     * folderpath is relative folder path from base
     */
    function &addFile($id, $filename, $path_relative, $folder_id, $user_id,
    $user_uploaded=0, &$tags = array(),
    $prefix = '',$view_publish_url='',&$fileInfo = array()) {
        $file = $fileInfo;

        $file['name'] = $filename;
        $file['folder_id'] = $folder_id;
        $file['user_uploaded'] = $user_uploaded;
        if ($file['name'] == 'Thumbs.db') {
            return;
        }
        $file['view_publish_url'] = $view_publish_url;

        // clean path_relative
        $path_relative = preg_replace('#[/\\\\]+#', '/', $path_relative);
        if ($path_relative == '/') {
            $path_relative = '';
        }

        $file['path_relative'] =  $path_relative.'/'.$prefix.$file['name'];

        $item = $this->getItem();
        $item->bind($file);
        if (!$item->store()) {
            return JError::raiseWarning( 500, $item->getError() );
        }

        // Update created_by
        if ($id == 0) {
            $item->updateCreatedBy($item->id,$user_id);
        } else {
            $item->updateModifiedBy($item->id,$user_id);
        }
        // bind the tags
        $this->_dataModel->replaceTagsForItem($item->id,$tags);
        return $item;
    }

    function addExternalVideo(&$values,&$tags,$user_id) {
        $item = $this->getItem();
        $item->bind($values);
        if (!$item->store()) {
            return JError::raiseWarning( 500, $item->getError() );
        }
        $id = $this->getState('id',0);
        // Update created_by
        if ($id == 0) {
            $item->updateCreatedBy($item->id,$user_id);
        } else {
            $item->updateModifiedBy($item->id,$user_id);
        }
        // bind the tags
        $this->_dataModel->replaceTagsForItem($item->id,$tags);
        return $item;
    }

    /**
     * Function to update media stored on the Hub (images/attachements)
     *
     */
    function updateMedia($values) {
        return $this->save($values);
    }

    function getPagination() {
        if (empty($this->_pagination)) {
            $this->_pagination = parent::getPagination();
        }
        return $this->_pagination;
    }

    /**
     * @param mediaIds array of ids
     * @return array objects
     */
    function getDetails($mediaIds) {
        return $this->_dataModel->getDetails($mediaIds);
    }

    /**
     * Tests if the given user is the creator of the media object
     * @param $mediaId int ID of the media object
     * @param $userId int ID of the user
     * @return boolean true if the given user id is the creator of the media, false otherwise
     */
    function isCreator($mediaId,$userId) {
        $table = $this->getResource();
        $table->load($mediaId);
        return $table->created_by == $userId;
    }

    /**
     * Clean the input data
     */
    function cleanData(&$values) {
        $values['title'] = $this->cleanText($values['title']);
        $values['description'] = $this->cleanText($values['description']);
    }
}