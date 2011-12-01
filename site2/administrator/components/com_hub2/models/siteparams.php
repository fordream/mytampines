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
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'hub2definition.php');
require_once('model.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.
'site.php');

class Hub2ModelSiteParams extends Hub2Model {

    function __construct($config=array()) {
        $this->_name = 'siteparams';
        parent::__construct($config);
    }

    /**
     * Returns true if the given values are validated for the item
     * @param $values
     * @param $errors an array of string values
     * @return boolean
     */
    function validateData($values, &$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        $return = true;
        $this->_validator->loadAndSetFieldValidateRules('siteparams.validation.rule');
        $return = $this->_validator->validate($item,$errors) && $return;
        // make sure name is unique
        $db = &JFactory::getDBO();
        $constraints = array();
        $constraints[] = 'name='.$db->Quote($values['name']);
        $id = $this->getState('id',0);
        if ($id) {
            $constraints[] = 'id<>'.$id;
        }
        if ($this->_dataModel->getCountForConstraint($constraints)) {
            $errors[] = JText::_('SITEPARAM_NAME_EXISTS');
            $return = false;
        }
        return $return;
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
        $count = $this->_dataModel->getValueCountForParameter($id);
        return $count == 0;
    }

    /**
     * delete a parameter
     * Ensures a parameter cannot be deleted if there are values associated with it
     * @return true or JError object
     */
    function delete($id) {
        $count = $this->_dataModel->getValueCountForParameter($id);
        if ($count == 0) {
            $result = $this->_dataModel->remove($id,$this->getResource());
            if (!$result) {
                $error = JError::raiseWarning(500,
                $this->_dataModel->getError());
                return $error;
            }
            return $result;
        } else {
            $error = JError::raiseWarning(500,
            JText::_("Cannot remove parameter since it has values associated"));
            return $error;
        }
    }

    /**
     * returns list of siteparam objects
     * @return array of Objects
     */
    public function getList() {
        return $this->_dataModel->getList();
    }

    /**
     * Get a Site parameter Object using the given id
     * @param id the id of the parameter to return
     * @return a JTable object or null if not found
     */
    public function getSiteParameter($id) {
        $table =& $this->getResource();
        $result = $table->load($id);
        if ($result && $table->id == $id) {
            return $table;
        } else {
            return null;
        }
    }

    /**
     * Get the list of values assigned to a site parameter, indexed by site id
     * @param id the id of the parameter to return
     * @return an associative array or null if not found, index for array is the site id
     */
    public function getSiteParameterValues($id) {
        $db = &JFactory::getDBO();
        $db->setQuery("select site_id,value from #__hub2_siteparams_values where
        siteparam_id={$id}");
        return $db->loadAssocList('site_id');
    }

    /**
     * Delete all site parameter values for a given site
     * @param $site_id int The ID of the site whose values are to be deleted
     * @return boolean true on success
     */
    function deleteSiteParamValuesForSite($site_id) {
        return $this->_dataModel->deleteSiteParamValuesForSite($site_id);
    }

    /**
     * Returns an HTML string that can be used to display the field
     * @param int id id of the parameter
     * @param string value current value for the field
     * @param string if for the input field to use
     * @param name name of the input field to use
     * @return an HTML string
     */
    public function getHTMLForField($id,$value,$fieldid,$name) {
        $html = '<input type="text" size="52"
        class="inputbox validate required custom-validate-length_0-255 @lbl"
        value="'.$value.'" id="'.$fieldid.'" name="'.$name.'">';
        return $html;
    }

    /**
     * Get the list of values assigned to site parameters for a site
     * @param int site_id the id of the site for which values are to be returned
     * @return an array of Objects with name, id, and value properties
     */
    public function getSiteParameterValuesForSiteWithDetails($site_id) {
        $db = &JFactory::getDBO();
        $db->setQuery("select name, description, id from #__hub2_siteparams");
        $result = $db->loadObjectList();
        $db->setQuery("select siteparam_id,value from
                    #__hub2_siteparams_values where site_id={$site_id}");
        $values = $db->loadAssocList('siteparam_id');
        foreach ($result as &$param) {
            $param->value = @$values[$param->id]['value'];
            $param->field = $this->getHTMLForField($param->id, $param->value,
            'siteparams_'.$param->id,'siteparams['.$param->id.']');
        }
        return $result;
    }

    /**
     * Get the list of values assigned to site parameters for a site
     * @param int site_id the id of the site for which values are to be returned
     * @return an array of Objects with site_id, siteparam_id, and value properties
     */
    public function getSiteParameterValuesForSite($site_id) {
        $db = &JFactory::getDBO();
        $db->setQuery("select * from #__hub2_siteparams_values where site_id={$site_id}");
        $values = $db->loadObjectList();
        return $values;
    }

    /**
     * Returns true if the given values are validated for the item
     * @param $values
     * @param $errors
     * @return boolean
     */
    function validateParamData($values, &$errors) {
        // need to get site details and set it to sites reference
        $siteModel = new Hub2ModelSite();
        $sites = $siteModel->getItems();
        foreach($sites as $site) {
            if (!array_key_exists($site->id,$values)) {
                $errors[] = JText::_('INVALID_VALUE_FOR_SITE');
                return false;
            }
            $val = $values[$site->id]['value'];
            if (!($site->id > 0 && strlen(trim($val)) > 0 )) {
                $errors[] = JText::_('INVALID_VALUE_FOR_SITE');
                return false;
            }
        }
        return true;
    }

    /**
     * Custom save method to save a list of values for various sites for a given param
     * @param array values an array of values
     */
    function &saveSiteParamValues( &$values ) {
        $id = $this->getState('id');

        $result = $this->_dataModel->saveSiteParamValues($id, $values, $this->getResource());

        return $result;
    }

    /**
     * @return an array indexed by the site param -> each item is an associative array with count
     */
    function getSiteCountForEachParam() {
        $db = $this->getDBO();
        $db->setQuery("select count(site_id) as count,siteparam_id from
        #__hub2_siteparams_values group by siteparam_id");
        return $db->loadAssocList('siteparam_id');
    }

    /**
     * Saves a list of values for parameters
     * @param int site_id - id of the site
     * @param array values - an array of values indexed by the parameter id
     */
    function saveSiteParamValuesForASite($site_id,$values) {
        $result = $this->_dataModel->saveSiteParamValuesForASite($site_id,$values);
        return $result;
    }

    /**
     * Returns value of a parameter specified by the id
     * This function should only be run on the site else will return null
     * @param int id of the parameter
     * @return mixed null if not found or a string
     */
    function getValueById($id) {
        if (ISSITE) {
            $db = $this->getDBO();
            $db->setQuery("select value from #__hub2_siteparams_values
            where siteparam_id={$id}");
            return $db->loadResult();
        } else {
            return null;
        }
    }

    /**
     * Returns value of a parameter specified by the name
     * This function should only be run on the site else will return null
     * @param string $name name of the parameter
     * @return mixed string or null if not found
     */
    function getValueByName($name) {
        if (ISSITE) {
            $db = $this->getDBO();
            $db->setQuery("select value from #__hub2_siteparams_values v
            inner join #__hub2_siteparams s on s.id=v.siteparam_id where s.name=".
            $db->Quote($name));
            return $db->loadResult();
        } else {
            return null;
        }
    }

    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
    }

}

?>