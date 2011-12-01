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
require_once('model.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'aliasHelper.php');
/**
 * Topic model
 *
 */
class Hub2ModelTopic extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'topic';
        parent::__construct($config);
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $values['id']=$this->getState('id');

        $result = $this->_dataModel->save($values, $this->getResource());

        return $result;
    }

    function canDelete($id) {
        $count = $this->_dataModel->getItemCountForTopic($id);
        $count1 = count($this->_dataModel->getChildren($id));
        return ($count == 0 && $count1 == 0);
    }

    function delete($id) {
        $count = $this->_dataModel->getItemCountForTopic($id);
        if ($count == 0) {
            $count1 = count($this->_dataModel->getChildren($id));
            if ($count1 == 0) {
                return $this->_dataModel->delete($id);
            } else {
                $error = JError::raiseWarning(0,'Cannot delete topic with ID - '.
                $id.' since it has children topics.');
                return $error;
            }
        } else {
            $error = JError::raiseWarning(0,'Cannot delete topic with ID - '.
            $id.' since it has associated items.');
            return $error;
        }
    }

    function validateData($values,&$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        // mapping of required fields;
        $this->_validator->loadAndSetFieldValidateRules('topic.validation.rule');
        $return = $this->_validator->validate($item,$errors);
        $db = &JFactory::getDBO();
        $constraints = array();
        $constraints[] = 'parent_id='.$db->Quote($values['parent_id']);
        $constraints[] = 'name='.$db->Quote($values['name']);
        $id = $this->getState('id',0);
        if ($id) {
            $constraints[] = 'id<>'.$id;
        }
        if ($this->_dataModel->getCountForConstraint($constraints)) {
            $errors[] = JText::_('Topic with same name already exists.');
            $return = false;
        }
        return $return;
    }

    /**
     *
     * @return an array of topic objects indexed by the topic id
     */
    function getChildren($id) {
        return $this->_dataModel->getChildren($id);
    }
    /**
     * return an array of ids, and title starting with the root
     *
     */
    function getPath($id) {

        if ($id == 0) {
            return array();
        }
        $array = $this->_dataModel->getPath('hub2_topics',$id);

        $return = array();
        foreach ($array as &$obj) {
            $return[] = $this->getItemSlug($obj);
        }
        return $return;
    }

    function getItemSlug(&$topic) {
        $idslug = $topic->id.':'.Hub2AliasHelper::buildAlias($topic->name);
        return $idslug;
    }

    function rebuildOnExternalSave() {
        return $this->_dataModel->rebuildOnExternalSave();
    }

    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
        $values['alias'] = $this->cleanText($values['alias']);
        $values['metadesc'] = $this->cleanText($values['metadesc']);
        $values['metakey'] = $this->cleanText($values['metakey']);
        $values['comments'] = $this->cleanText($values['comments']);
    }

    function isChild($childId,$parentId) {
        return $this->_dataModel->isChild('hub2_topics',$childId,$parentId);
    }
}
