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

class Hub2ModelPostcode extends Hub2Model {


    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'postcode';
        parent::__construct($config);
    }


    private static function &getPropagator() {
        require_once(dirname(__FILE__).DS.'..'.DS.'services'.DS.
        'postcodePropagationService.php');
        static $instance = false;
        if (!$instance) {
            $instance = new Hub2ServicePostcodePropagation();
        }
        return $instance;
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
        $this->_validator->loadAndSetFieldValidateRules('postcode.validation.rule');
        $return = $this->_validator->validate($item,$errors);
        $db = &JFactory::getDBO();
        $constraints = array();
        $constraints[] = 'postcode='.$db->Quote($values['postcode']);
        $constraints[] = 'name='.$db->Quote($values['name']);
        $id = $this->getState('id',0);
        if ($id) {
            $constraints[] = 'id<>'.$id;
        }
        if ($this->_dataModel->getCountForConstraint($constraints)) {
            $errors[] = JText::_('Postcode with same details already exists.');
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
        $sites          = JArrayHelper::getValue( $request, 'jxform_site', array(), 'array' );

        if ($params) {
            $registry = new JRegistry();
            $registry->loadArray( $params );
            $values['params'] = $registry->toString();
        }
        $result = $this->_dataModel->save($values, $this->getResource());

        if (!JError::isError($result)) {
            $obj = $this->getItemById($result);
            // update site/category mapping
            $insert_id=(int)$obj->id;
        }

        // delete old connections first before saving new connections
        $old_sites = $this->_dataModel->getSiteIDsForPostcode($insert_id);
        // first collect the sites this needs to be removed from
        // not directly removing to reduce the number of calls
        $removeFromSites = array();
        // need to delete from unselected sites
        foreach ($old_sites as $old_site) {
            if (!in_array($old_site,$sites)) {
                // we now have an unselected site
                $removeFromSites[] = $old_site;
            }
        }
        if (!empty($removeFromSites)) {
            $errors = array();
            $siteModel = new Hub2ModelSite();
            $dsites = $siteModel->getDetails($removeFromSites);
            $dresult =  $this->getPropagator()->removePostcodeFromSites(
            $insert_id,$dsites,$errors);
            if (!$dresult) {
                JError::raiseNotice('ERROR_CODE',
                JText::_('Could not remove postcode from site(s)').
                    '<br />'.implode('<br />',$errors));
            }
            // remove mapping from error free sites
            foreach ($removeFromSites as $old_site) {
                if (!array_key_exists($old_site,$errors)) {
                    $this->_dataModel->removeSiteFromPostcode($old_site,$insert_id);
                }
            }
        }

        // save to suggested sites connections
        foreach ($sites as $site_id) {
            $this->_dataModel->addSiteToPostcode($site_id,$insert_id);
        }

        return $result;
    }

    function canDelete($id) {
        $count = $this->_dataModel->getItemCountForPostcode($id);
        return ($count == 0);
    }

    function delete($id) {
        if ($this->canDelete($id)) {
            return $this->_dataModel->remove($id);
        } else {
            $error = JError::raiseWarning(0,'Cannot delete postcode with ID - '.
            $id.' since it has associted items.');
            return $error;
        }
    }

    function getSitesForPostcode($id) {
        return $this->_dataModel->getSitesForPostcode($id);
    }

    function getItemSlug(&$postcode) {
        require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'aliasHelper.php');
        $idslug = $postcode->id.':'.Hub2AliasHelper::buildAlias($postcode->name);
        return $idslug;
    }

    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
        $values['postcode'] = $this->cleanText($values['postcode']);
        $values['metadesc'] = $this->cleanText($values['metadesc']);
        $values['metakey'] = $this->cleanText($values['metakey']);
        $values['comments'] = $this->cleanText($values['comments']);
    }

    /**
     * Returns an associted array of all names and ids in the DB
     * @return array Associated array
     */
    function getAllNamesAndIds() {
        return $this->_dataModel->getAllFieldValues(array('id','name'));
    }

    /**
     * Returns an associted array of all postcodes and ids in the DB
     * @return array Associated array
     */
    function getAllPostcodesAndIds() {
        return $this->_dataModel->getAllFieldValues(array('id','postcode'));
    }
}

?>