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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.
'site.php');

/**
 * Region model
 *
 */
class Hub2ModelSocialTokens extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'socialtokens';
        parent::__construct($config);
    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $values['id']=$this->getState('id');
        if($values['media_type'] == "Twitter") {
            $values['metadata'] = $this->prepareMetadataForTwitter($values);
        }
        $result = $this->_dataModel->save($values, $this->getResource());
        return $result;
    }

    /**
     * Delete all social tokens for a given site
     * @param $site_id int The ID of the site whose values are to be deleted
     * @return boolean true on success
     */
    function deleteSocialTokensForSite($site_id) {
        return $this->_dataModel->deleteSocialTokensForSite($site_id);
    }

    function delete($id) {
        return $this->_dataModel->remove($id);
    }

    private function prepareMetadataForTwitter($values) {
        $metadata = $values['access_token'];
        $metadata .= "^^^".$values['access_token_secret'];
        $metadata .= "^^^".$values['consumer_key'];
        $metadata .= "^^^".$values['consumer_secret'];
        return $metadata;
    }

    function validateData($values,&$errors) {
        $item = $this->getItem();
        // mapping of required fields;
        if($values['media_type'] == "Twitter") {
            $values['metadata'] = $this->prepareMetadataForTwitter($values);
        }
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        $this->_validator->loadAndSetFieldValidateRules('socialtokens.validation.rule');
        $return = $this->_validator->validate($item,$errors);
        //ask datamodel to check if site_id and key exists
        //if true then return false $errors[] = "Mapping ";
        if($this->_dataModel->checkTheSite_id_and_key_exists($values)>0 and $item->id == 0){
            $errors[] = JText::_("A value already exists for this site");
            $return = false;
        }
        return $return;
    }

    //
    function getSiteTokenByType($type) {
        $item = $this->_dataModel->getSiteItemByType($type);
        return $item->metadata;
    }

    function getSiteUrl($type) {
        $item = $this->_dataModel->getSiteUrl($type);
        return $item->url;
    }
}