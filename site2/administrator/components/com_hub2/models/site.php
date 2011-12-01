<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jximport( 'jxtended.database.query');
require_once( 'model.php' );
require_once( 'siteparams.php' );
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.
'tables'.DS.'site.php');


class Hub2ModelSite extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'site';
        parent::__construct($config);
    }


    /**
     * Override _getList function for JModel
     * Returns an object list with id as the key - requored for contentpropagationmanagement view
     */
    function &_getList( $query, $limitstart=0, $limit=0 ) {
        $this->_db->setQuery( $query, $limitstart, $limit );
        $result = $this->_db->loadObjectList('id');

        return $result;
    }


    function getAssignedLanguages() {
        return $this->_dataModel->getDistinctLanguages();
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
        $this->_validator->loadAndSetFieldValidateRules('site.validation.rule');
        if ($item->is_template == '0') {
            if (!isset($item->template_id) || $item->template_id == '0') {
                $errors[] = JText::_('Template needs to be
                		selected if this site is not a template');
                $return = false;
            }
        }
        $return = $this->_validator->validate($item,$errors) && $return;
        $db = &JFactory::getDBO();
        $constraints = array();
        $constraints[] = 'url='.$db->Quote($values['url']);
        $id = $this->getState('id',0);
        if ($id) {
            $constraints[] = 'id<>'.$id;
        }
        if ($this->_dataModel->getCountForConstraint($constraints)) {
            $errors[] = JText::_('Site with same URL already exists.');
            $return = false;
        }
        $request        = $this->getState( 'request' );
        $categories     = JArrayHelper::getValue( $request, 'jxform_category', array(), 'array' );
        if (!$this->_dataModel->getParentsPath($categories)) {
            $errors[] = JText::_('Please select the parent categories too.');
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
        $params         = JArrayHelper::getValue( $request['jxform'], 'params', array(), 'array' );
        $siteparams     = JRequest::getVar( 'siteparams', array(), 'post', 'array' );

        // handle the regions
        $regions        = JArrayHelper::getValue( $request, 'jxform_region', array(), 'array' );
        // handle the categories
        $categories     = JArrayHelper::getValue( $request, 'jxform_category', array(), 'array' );
        // handle the postcodes
        $postcodes     = JArrayHelper::getValue( $request, 'jxform_postcode', array(), 'array' );
        if ($params) {
            $registry = new JRegistry();
            $registry->loadArray( $params );
            $values['params'] = $registry->toString();
        }

        // save current primary key to pkey2 if site already exists
        if ($values['id'] !== 0) {
            $table = $this->getResource();
            $table->load($values['id']);
            $values['pkey2'] = $table->pkey1;
        }
        $result = $this->_dataModel->save($values, $this->getResource());
        if (!JError::isError($result)) {
            if (!$this->_dataModel->saveSiteRegions($result,$regions)) {
                $result = JError::raiseWarning(100,"Could not save site regions");
            }
            if (!$this->_dataModel->saveSiteCategories($result,$categories)) {
                $result = JError::raiseWarning(100,"Could not save site categories");
            }
            if (!$this->_dataModel->saveSitePostcodes($result,$postcodes)) {
                $result = JError::raiseWarning(100,"Could not save site postcodes/neighbourhoods");
            }

            // save the site parameters
            $siteParamsModel = new Hub2ModelSiteParams();
            if (JError::isError(
            $siteParamsModel->saveSiteParamValuesForASite($result,$siteparams))) {
                $result = JError::raiseWarning(100,"Could not save site parameters");
            }
        }
        return $result;
    }

    function canDelete($id) {
        $count = $this->_dataModel->getSiteCountForTemplate($id);
        return $count == 0;
    }

    /**
     * delete an item - simply disables it.
     * Ensures a template cannot be disabled till there are sites associated with it
     * @return true or JError object
     */
    function delete($id) {
        require_once('socialtokens.php');
        require_once('siteregionrelations.php');
        require_once('sitecategoryrelations.php');
        require_once('sitepostcoderelations.php');
        $count = $this->_dataModel->getSiteCountForTemplate($id);
        if ($count == 0) {
            $relations = new Hub2ModelSiteRegionRelations();
            $success = $relations->deleteSiteRelations($id);

            if ($success) {
                $relations = new Hub2ModelSiteCategoryRelations();
                $success = $relations->deleteSiteRelations($id);
            } else {
                $error = JError::raiseWarning(501,
                JText::_('Could not delete the region mapping for this site. Please try again'));
                return $error;
            }

            if ($success) {
                $relations = new Hub2ModelSitePostcodeRelations();
                $success = $relations->deleteSiteRelations($id);
            } else {
                $error = JError::raiseWarning(501,
                JText::_('Could not delete category mapping for this site. Please try again'));
                return $error;
            }

            if ($success) {
                $relations = new Hub2ModelSiteParams();
                $success = $relations->deleteSiteParamValuesForSite($id);
            } else {
                $error = JError::raiseWarning(501,
                JText::_('Could not delete postcode mapping for this site. Please try again'));
                return $error;
            }

            if ($success) {
                $relations = new Hub2ModelSocialTokens();
                $success = $relations->deleteSocialTokensForSite($id);
            } else {
                $error = JError::raiseWarning(501,
                JText::_('Could not delete parameters for this site. Please try again'));
                return $error;
            }

            if ($success) {
                return $this->_dataModel->disable($id);
            } else {
                $error = JError::raiseWarning(501,
                JText::_('Could not delete social tokens for this site. Please try again'));
                return $error;
            }
        } else {
            $error = JError::raiseWarning(501,'Cannot delete template site with ID - '.
            $id.' since it has associted sites.');
            return $error;
        }
    }

    /**
     * Get the timezone associated with the site given by the sitte id
     * @param int $siteId ID of the site
     * @return string A PHP timezone
     */
    function getTimezone($siteId) {
        $table = new Hub2TableSite($this->_db);
        $table->load($siteId);
        $sparams = new JParameter($table->params);
        $timezone = $sparams->get('timezone');
        return $timezone;
    }

    /**
     * Get the Key for the site used for SOAP services (to be used on the local site)
     *
     */
    function getMyKeys() {
        $keys = $this->_dataModel->getKeysForSite();
        return $keys;
    }

    /**
     * Returns the local site details
     * @return Array an associative array
     */
    function getLocalSiteDetails() {
        return $this->_dataModel->getLocalSiteDetails();
    }

    /**
     * Returns details of the sites with the given ids
     * @param array $ids - array of ids
     * @return Array of Objects
     */
    function getDetails($ids) {
        return $this->_dataModel->getDetails($ids);
    }

    /**
     * Returns details of all the sites
     * @return Array of Objects
     */
    function getAllSiteDetails() {
        return $this->_dataModel->getAllSites();
    }

    /**
     * Clean data before storing into database
     * @param $values array of values to store
     */
    function cleanData(&$values) {
        $values['name'] = $this->cleanText($values['name']);
        $values['url'] = $this->cleanURI($values['url']);
        $values['hub2_url'] = $this->cleanURI($values['hub2_url']);
        $values['qa_url'] = $this->cleanURI($values['qa_url']);
        $values['pkey1'] = $this->cleanText($values['pkey1']);
        $values['dbhost'] = $this->cleanText($values['dbhost']);
        $values['dbuser'] = $this->cleanText($values['dbuser']);
        $values['dbname'] = $this->cleanText($values['dbname']);
        $values['dbprefix'] = $this->cleanText($values['dbprefix']);
    }

}

?>