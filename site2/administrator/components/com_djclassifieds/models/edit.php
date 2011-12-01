<?php
/**
 * @version		1.1
 * @package		DJ Classifieds
 * @subpackage	DJ Classifieds Component
 * @copyright	Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
 * @license		http://www.gnu.org/licenses GNU/GPL
 * @autor url    http://design-joomla.eu
 * @autor email  contact@design-joomla.eu
 * @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
 *
 *
 * DJ Classifieds is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ Classifieds is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

//Amol
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'site.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'model.php');





class DjclassifiedsModelEdit extends JModel

{

    function getCat()

    {
        $option = JRequest::getVar('option','com_djclassifieds');

        JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
        $row =& JTable::getInstance('categories','Table');
        $cid = JRequest::getVar('cid', array(0), '', 'array' );
        $id = $cid[0];
        $row->load( $id );
        return $row;
    }

    var $_categories;
    var $wynik = array();

    function checkChilds(&$tab){


        for($i=0; $i< count($tab); $i++){
            $wynik[] = $this->_categories[$i];
            $query = "SELECT * FROM #__djcf_categories WHERE parent_id LIKE ".$this->_categories[$i]->id."";
            $wyni = $this->_getList($query,0,0);
            $wynik = array_merge($wynik,$wyni);

        }
        $this->checkChilds($wyni);
    }

    function getCategories(){
        $cid = JRequest::getVar('cid', array(0), '', 'array' );
        $id = $cid[0];
        if(!$this->_categories){

            $query = "SELECT * FROM #__djcf_categories WHERE id !=".$id." ORDER BY parent_id";

            $this->_categories = $this->_getList($query, 0, 0);

        }



        return $this->_categories;
    }

    /* This function returns List all regions with childs*/
    public function getHub2Regions(){
        $model      = Hub2DataModel::getInstance( 'region');

        $result	= $model->getRegions();
        //$options[] = JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --');
        foreach ($result as $item) {
            if ((int)$item->id !== 0) {
                $name = $item->name;
                if ($item->level > 1) {
                    $name = str_repeat('- ',(int)$item->level-1).$item->name;
                }
                $options[]  = JHTML::_('select.option', $item->id, $name);
            }
        }
        return $options;

    }

    /* This function returns Array of regions selected for category */
    public function getSelectedHub2Regions(){
        $selctd	 = null;
        if(JRequest::getVar('task') == 'edit') {
            $cid = JRequest::getVar('cid', array(0), '', 'array' );
            $id = $cid[0];

            $db =& JFactory::getDBO();
            $query = 'SELECT region_id FROM #__hub2_classifieds_cat_region_relation where cat_id='.$id;
            $db->setQuery($query);
            $selctd = $db->loadResultArray();
        }
        return $selctd;
    }

    /* This function Deletes the ad data from Region Table controller->saveitem() */
    public function deleteAdDataFromRegion($adId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
		    	'DELETE FROM #__hub2_classifieds_ad_region_relation '
		    	. 'WHERE ad_id='.$adId;
		    	$db->setQuery($query);
		    	if(!$db->query()) {
		    	    $errArr['success'] = false;
		    	    $errArr['msg']=$db->getError();
		    	}

		    	return $errArr;

    }

    /* This function Deletes the ad data from Site Table*/
    public function deleteAdDataFromSite($adId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
		    	'DELETE FROM #__hub2_classifieds_ad_site_relation '
		    	. 'WHERE ad_id='.$adId;
		    	$db->setQuery($query);
		    	if(!$db->query()) {
		    	    $errArr['success'] = false;
		    	    $errArr['msg']=$db->getError();
		    	}

		    	return $errArr;

    }

    /* This function Adds the ad specific data to Region Table */
    public function addAdDataToRegion($adId,$regionId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
		    	'INSERT INTO #__hub2_classifieds_ad_region_relation '
		    	. ' (id, ad_id, region_id) VALUES (NULL,'
		    	. $adId . ', ' . $regionId . ') ON DUPLICATE KEY update ad_id='.$adId;
		    	$db->setQuery($query);
		    	if(!$db->query()) {
		    	    $errArr['success'] = false;
		    	    $errArr['msg']=$db->getError();
		    	}
		    	return $errArr;

    }

    /* This function Adds the ad specific data to Site Table */
    public function addAdDataToSite($adId,$nsiteId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
			'INSERT INTO #__hub2_classifieds_ad_site_relation '
			. ' (id, ad_id, site_id) VALUES (NULL,'
			. $adId . ', ' . $nsiteId . ') ON DUPLICATE KEY update ad_id='.$adId;
			$db->setQuery($query);
			if(!$db->query()) {
			    $errArr['success'] = false;
			    $errArr['msg']=$db->getError();
			}

			return $errArr;


    }

    /* This function Returns the Previous Sites for the specific adId*/
    public function getPrevSitesForAd($adId){
        $db =& JFactory::getDBO();
        $query = 'SELECT site_id FROM #__hub2_classifieds_ad_site_relation where ad_id='.$adId;
        $db->setQuery($query);
        return $db->loadResultArray();
    }

    /* This function Returns the Previous Sites for the specific CatId*/
    public function getPrevSitesForCat($catId){
        $db =& JFactory::getDBO();
        $query = 'SELECT site_id FROM #__hub2_classifieds_cat_site_relation where cat_id='.$catId;
        $db->setQuery($query);
        return $db->loadResultArray();
    }

    /* This function Deletes the Cat data from Region Table controller->save() */
    public function deleteCatDataFromRegion($catId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
		    	'DELETE FROM #__hub2_classifieds_cat_region_relation '
		    	. 'WHERE cat_id='.$catId;
		    	$db->setQuery($query);
		    	if(!$db->query()) {
		    	    $errArr['success'] = false;
		    	    $errArr['msg']=$db->getError();
		    	}

		    	return $errArr;

    }

    /* This function Deletes the Cat data from Site Table*/
    public function deleteCatDataFromSite($catId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
		    	'DELETE FROM #__hub2_classifieds_cat_site_relation '
		    	. 'WHERE cat_id='.$catId;
		    	$db->setQuery($query);
		    	if(!$db->query()) {
		    	    $errArr['success'] = false;
		    	    $errArr['msg']=$db->getError();
		    	}

		    	return $errArr;

    }

    /* This function Adds the Cat specific data to Region Table */
    public function addCatDataToRegion($catId,$regionId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
		    	'INSERT INTO #__hub2_classifieds_cat_region_relation '
		    	. ' (id, cat_id, region_id) VALUES (NULL,'
		    	. $catId . ', ' . $regionId . ') ON DUPLICATE KEY update cat_id='.$catId;
		    	$db->setQuery($query);

		    	if(!$db->query()) {
		    	    $errArr['success'] = false;
		    	    $errArr['msg']=$db->getError();
		    	}

		    	return $errArr;

    }

    /* This function Adds the Cat specific data to Site Table */
    public function addCatDataToSite($catId,$nsiteId){
        $db =& JFactory::getDBO();
        $errArr['success'] = true;
        $query =
			'INSERT INTO #__hub2_classifieds_cat_site_relation '
			. ' (id, cat_id, site_id) VALUES (NULL,'
			. $catId . ', ' . $nsiteId . ') ON DUPLICATE KEY UPDATE cat_id='.$catId;
			$db->setQuery($query);
			if(!$db->query()) {
			    $errArr['success'] = false;
			    $errArr['msg']=$db->getError();
			}

			return $errArr;


    }

   	public function getCatRegions($catId){
   	    $model      = Hub2DataModel::getInstance( 'region');
   	    $allRegions = $model->getPossibleParents();
   	    $db =& JFactory::getDBO();

   	    $query = 'SELECT region_id FROM #__hub2_classifieds_cat_region_relation where cat_id='.$catId;
   	    $db->setQuery($query);
   	    $result = $db->loadResultArray();

   	    return $result;

   	}

   	public function getAllChildRegions($regionIds) {
   	    $regionDataModel = Hub2DataModel::getInstance('region','Hub2DataModel');
   	    $childRegionIds = $regionDataModel->getChildIDs($regionIds);
   	    return $childRegionIds;
   	}


   	public function getSitesForRegionByLang($regionId,$lang='en') {
   	    $db =& JFactory::getDBO();
   	    $query = 'SELECT site_id FROM #__hub2_sites_region_relations '.
		' LEFT JOIN #__hub2_sites ON #__hub2_sites_region_relations '.
		'.site_id=#__hub2_sites.id WHERE #__hub2_sites.lang='.$db->Quote($lang).
		' AND region_id=' . $regionId;
   	    $db->setQuery($query);
   	    $result = $db->loadResultArray();
   	    return $result;
   	}
   	public function getSiteDetails() {
   	    $ssiteModel = new Hub2ModelSite(); // Amol
   	    $result = $ssiteModel->getLocalSiteDetails(); //Amol
   	    return $result;
   	}

   	public function getRegionIdForSiteId($siteId,$lang='en') {
   	    $db =& JFactory::getDBO();
   	    $query = 'SELECT region_id FROM #__hub2_sites_region_relations '.
		' LEFT JOIN #__hub2_sites ON #__hub2_sites_region_relations '.
		'.site_id=#__hub2_sites.id WHERE #__hub2_sites.lang='.$db->Quote($lang).
		' AND site_id=' . $siteId;
   	    $db->setQuery($query);
   	    // error_log($query);
   	    $result = $db->loadResultArray();
   	    return $result;
   	}



}
