<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 * @author		joseph
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jximport( 'jxtended.database.query');
require_once('model.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'aliasHelper.php');

class Hub2ModelTag extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'tag';
        parent::__construct($config);
    }

    /**
     * Returns true if the given values are validated for the item
     * @param $values
     * @param $errors
     * @return boolean
     */
    function validateData($values, &$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        $this->_validator->loadAndSetFieldValidateRules('tag.validation.rule');
        $return = $this->_validator->validate($item,$errors);
        $db = &JFactory::getDBO();
        $constraints = array();
        $constraints[] = 'name='.$db->Quote($values['name']);
        $id = $this->getState('id',0);
        if ($id) {
            $constraints[] = 'id<>'.$id;
        }
        if ($this->_dataModel->getCountForConstraint($constraints)) {
            $errors[] = JText::_('Tag with same name already exists.');
            $return = false;
        }
        return $return;
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $values['id']=$this->getState('id');
        $request        = $this->getState( 'request' );
        $params         = JArrayHelper::getValue( $request, 'jxformparams', array(), 'array' );

        if ($params) {
            $registry = new JRegistry();
            $registry->loadArray( $params );
            $values['params'] = $registry->toString();
        }
        $result = $this->_dataModel->save($values, $this->getResource());

        return $result;
    }

    function canDelete($id) {
        $count = $this->_dataModel->getItemCountForTag($id);
        return ($count == 0);
    }

    /**
     * delete an item - simply disables it.
     * Ensures a template cannot be disabled till there are sites associated with it
     * @return true or JError object
     */
    function delete($id) {
        $count = $this->_dataModel->getItemCountForTag($id);
        if ($count == 0) {
            return $this->_dataModel->remove($id);
        } else {
            $error = JError::raiseWarning(0,'Cannot delete tag with ID - '.
            $id.' since it has associated items.');
            return $error;
        }
    }

    function getItemSlug(&$tag) {
        if (empty($tag->alias)) {
            $idslug = $tag->id.':'.Hub2AliasHelper::buildAlias($tag->name);
        } else {
            $idslug = $tag->id.':'.$tag->alias;
        }
        return $idslug;
    }

    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
        $values['alias'] = $this->cleanText($values['alias']);
        $values['metadesc'] = $this->cleanText($values['metadesc']);
        $values['metakey'] = $this->cleanText($values['metakey']);
        $values['comments'] = $this->cleanText($values['comments']);
    }

}

?>