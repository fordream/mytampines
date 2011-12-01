<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jximport( 'jxtended.database.query' );
jximport( 'jxtended.form.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once('model.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'mediaHelper.php');

/**
 * Folder model
 *
 */
class Hub2ModelFolder extends Hub2Model {

    var    $_basepath;

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $jApp = &JFactory::getApplication();
        $this->_name = 'folder';
        parent::__construct($config);

        $component = &JComponentHelper::getComponent( 'com_hub2' );
        $params = new JParameter( $component->params );
        $this->_basepath = $params->get('imagefolder','images/stories');
        // check if folder count is zero then add a folder
        if ($this->_dataModel->getFolderCount()== 0) {
            $this->addDefaultRootFolder();
        }

    }

    function getCurrentFolderID() {
        $folder_id = $this->getState('folder_id');

        // If undefined, set to empty
        if ($folder_id == 'undefined' || $folder_id == 0) {
            $folder_id = $this->_dataModel->getRootFolderId();
        }
        return $folder_id;
    }

    function &getForm() {
        JXFormHelper::addIncludePath( JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'models' );
        return parent::getForm();
    }

    /**
     * assumes the folder_id set in the model state
     */
    function getFolders() {
        // Get current path from request
        $folder_id = $this->getState('folder_id');

        // If undefined, set to empty
        if ($folder_id == 'undefined' || $folder_id == 0) {
            $folder_id = $this->_dataModel->getRootFolderId();
        }

        $folders = $this->_dataModel->getFolders($folder_id);
        return $folders;
    }


    /**
     * @return int number of media items in the folder
     *
     */
    function getItemCount($id) {
        return $this->_dataModel->getMediaCount($id);
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $jApp = &JFactory::getApplication();

        // make physical and also add to DB
        $folder = $values['name'];
        $parent_id = $values['parent_id'];
        $helper = new Hub2MediaHelper();
        $success =  $helper->createFolder($folder,$parent_id,$this);
        return $success;
    }

    function addFolder(&$table) {
        return $this->_dataModel->addFolder($table);
    }

    function addDefaultRootFolder() {
        // store in table
        $table = $this->getResource();
        $table->name = ''; // no name for the root folder
        $table->path_relative = '/'; // relative to the root it is empty
        $table->size = 0;
        $table->files = 0;
        $table->folders = 0;
        $table->parent_id = 0;
        $table->hidden_folder = 0;
        $this->addFolder($table);
    }

    function validateData($values,&$errors) {
        if (!$this->checkFolderExists($values['parent_id'])) {
            $errors[] = "Parent folder does not exist";
        }
        if (!$this->canAddSubFolder($values['name'],$values['parent_id'])) {
            $errors[] = "Folder with that name already exists";
        }
        if (stripos($values['name'],DS) !== false) {
            $errors[] = "Folder name is invalid";
        }
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        // mapping of required fields;
        $this->_validator->loadAndSetFieldValidateRules('folder.validation.rule');
        $this->_validator->validate($item,$errors);
        return count($errors) == 0;
    }

    function checkFolderExists($folder_id) {
        $table = $this->getResource();
        $table->load($folder_id);
        return ($table->id == $folder_id);
    }

    function canAddSubFolder($subFolderName, $parent_id) {
        if (!$this->_dataModel->canAddSubFolderWithName($subFolderName,$parent_id)) {
            return false;
        }
        $table = $this->getResource();
        $table->load($parent_id);

        // check physical path
        if (JFolder::exists(JPATH_SITE.DS.$this->_basepath.DS.
        $table->path_relative.DS.$subFolderName)) {
            return false;
        }
        return true;
    }

    function getBasePath() {
        return $this->_basepath;
    }

    function getFolderForPath($relative_path) {
        $res = $this->_dataModel->getFolder($relative_path);
        return $res;
    }

    function exists($id) {
        $table = $this->getResource();
        $table->load($id);
        return !($table->id == null || $table->id == 0);
    }

    function getPathFor($id) {
        $table = $this->getResource();
        $table->load($id);
        return $table->path_relative;
    }

    function getSubFolders($id,$showhidden=false) {
        return $this->_dataModel->getSubFolders($id,$showhidden);
    }

    /**
     * Return folder object for storing user images. Create folder if required
     */
    function getUserimageFolder($userId) {
        $component = &JComponentHelper::getComponent( 'com_hub2' );
        $params = new JParameter( $component->params );
        $folderPath = $params->get('userimagefolder','userimages');
        $userimageFolder = $this->getFolderForPath('/'.$folderPath);
        $helper = new Hub2MediaHelper();
        if (!$userimageFolder) {
            $userimageFolder = $helper->createFolder($folderPath,
            $this->_dataModel->getRootFolderId(),$this);
        }
        $folder = $helper->createFolder($userId,$userimageFolder->id,$this);
        return $folder;
    }
    /**
     * clean input values
     * @param $values
     */
    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
    }
}