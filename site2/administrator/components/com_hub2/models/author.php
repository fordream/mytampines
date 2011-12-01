<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jimport( 'joomla.application.component.model');
jximport( 'jxtended.database.query');
require_once('authormediarelations.php');
require_once('model.php');

class Hub2ModelAuthor extends Hub2Model {

    static $authorNames;

    function __construct($config=array()) {
        $this->_name = 'author';
        $this->authorNames = array();
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
        $return = true;
        $this->_validator->loadAndSetFieldValidateRules('author.validation.rule');
        $return = $this->_validator->validate($item,$errors) && $return;
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

        if (!JError::isError($result)) {
            // save the media
            $authorMediaRelationModel = new Hub2ModelAuthorMediaRelations();
            $res = $authorMediaRelationModel->updateMedia( $result, $values['media']);
            if (!$res) {
                JError::raiseNotice('ERROR_CODE',
                JText::_($authorMediaRelationModel->getError())
                );
            }

        }
        return $result;
    }


    /**
     * delete an item - simply disables it.
     * Ensures an author cannot be disabled till there are items associated with it
     * @return true or JError object
     */
    function delete($id) {
        $count = $this->_dataModel->getItemCountForAuthor($id);
        if ($count == 0) {
            // delete media relations
            $relations = new Hub2ModelAuthorMediaRelations();
            $success = $relations->deleteAuthorRelations($id);
            if ($success) {
                return $this->_dataModel->remove($id);
            } else {
                $error = JError::raiseWarning(0,'Could delete media relations');
                return $error;
            }
        } else {
            $error = JError::raiseWarning(0,'Cannot delete author with ID - '.
            $id.' since it has associted items.');
            return $error;
        }
    }

    public function &getAuthorField($id, $field) {
        if (empty($this->authorNames)) {
            $this->authorNames = $this->_dataModel->getAuthorListById();
        }
        if (array_key_exists($id,$this->authorNames)) {
            return $this->authorNames[$id][$field];
        } else {
            $r = null;
            return $r;
        }
    }

    public function getList() {
        return $this->_dataModel->getList();
    }

    /**
     * Method to get generic site author to use for item submitted from site
     * via registered site user. It assumes that an author (and corr. user) is
     * set up by admin.
     * @return mixed id of author if exists. False otherwise
     */
    public function getGenericSiteAuthorId() {
        return $this->_dataModel->getGenericSiteAuthorId();
    }

    public function getAuthor($id) {
        $table =& $this->getResource();
        $result = $table->load($id);
        if ($result && $table->id == $id) {
            return $table;
        } else {
            return null;
        }
    }

    public function canDelete($id) {
        $count = $this->_dataModel->getItemCountForAuthor($id);
        return $count == 0;
    }

    function cleanData(&$values) {
        $values['fullname'] = $this->cleanText($values['fullname']);
        $values['alias'] = $this->cleanText($values['alias']);
    }
}

?>