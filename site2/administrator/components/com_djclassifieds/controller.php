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
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
$lang = & JFactory::GetLanguage();
$lang->load('com_djclassifieds');

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'services'.DS.
'djClassifiedsPropagationService.php');

class DjclassifiedsController extends JController
{
    var $_itemProp;
    function __construct($default = array ())
    {
        parent::__construct($default);

        $this->_itemProp = new ItemPropagation();

        $this->registerTask('add', 'edit');
        $this->registerTask('addItem', 'editItem');
        $this->registerTask('apply', 'save');
        $this->registerTask('applyItem', 'saveItem');
        $this->registerTask('unpublish', 'publish');
        $this->registerTask('applyConfig', 'saveConfig');

        $this->footer='<br /><div style="margin: 0 auto; text-align:
		center; width: 100%">Hub2 Classifieds</div>';
    }

    function display()
    {
        JRequest::setVar('view', 'categories');
        parent::display(); echo $this->footer;
    }



    function items()
    {

        JRequest::setVar('view', 'items');
        parent::display(); echo $this->footer;
    }

    function config()
    {
        JRequest::setVar('view', 'config');
        parent::display(); echo $this->footer;
    }

    function edit()
    {
        JRequest::setVar('view', 'edit');
        parent::display(); echo $this->footer;
    }

    function nocategoryitems()
    {
        JRequest::setVar('view', 'nocategoryitems');
        parent::display(); echo $this->footer;
    }

    function editItem()
    {
        JRequest::setVar('view', 'editItem');
        parent::display(); echo $this->footer;
    }




    function save()
    {
        $jApp = &JFactory::getApplication();
        /* Amol Start*/
        $regionIds = JRequest::getVar('regionMulti', null, 'POST', 'array');
        if(!count($regionIds)) {
            echo "<script> alert('Select Region Id or Ids');
				window.history.go(-1); </script>\n";
            exit ();
        }
        /* Amol End*/

        $row = & JTable::getInstance('categories', 'table');
        $par = &JComponentHelper::getParams( 'com_djclassifieds' );
        if (!$row->bind(JRequest::get('post')))
        {
            echo "<script> alert('".$row->getError()."');
			window.history.go(-1); </script>\n";
            exit ();
        }



        if(JRequest::getVar('del_icon', '0','','int')){
            $path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/".$row->icon_url;
            //deleting the main image
            if (JFile::exists($path_to_delete))
            {
                JFile::delete($path_to_delete);
            }
            //deleting icon of image
            if (JFile::exists($path_to_delete.'.ths.jpg'))
            {
                JFile::delete($path_to_delete.'.ths.jpg');
            }
            $icon_url='';
        }

        //add icon
        $pliki = $_FILES['icon'];


        if (substr($pliki['type'], 0, 5) == "image")
        {
            $path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/".$row->icon_url;
            //deleting the main image
            if (JFile::exists($path_to_delete))
            {
                JFile::delete($path_to_delete);
            }
            //deleting icon of image
            if (JFile::exists($path_to_delete.'.ths.jpg'))
            {
                JFile::delete($path_to_delete.'.ths.jpg');
            }

            if(count($pliki['name'])>0 && $row->id==0){
                $query = "SELECT id FROM #__djcf_categories ORDER BY id DESC LIMIT 1";
                $db =& JFactory::getDBO();
                $db->setQuery($query);
                $last_id =$db->loadResult();
                $last_id++;
            }else{
                $last_id= $row->id;
            }


            $nazwa = 'cat'.$last_id.'_'.$pliki['name'];
            $icon_url = $nazwa;
            $sciezka = JPATH_BASE."/../components/com_djclassifieds/images/".$nazwa;
            move_uploaded_file($pliki['tmp_name'], $sciezka);

            $nw = $par->get('catth_width',-1);
            $nh = $par->get('catth_height',-1);
            $this->makeimg($sciezka, $nw, $nh, 'ths');
        }

        $row->icon_url = $icon_url;
        $row->price=$row->price*100;

        if(!$row->ordering){
            $query = "SELECT ordering FROM #__djcf_categories WHERE parent_id = ".$row->parent_id." ORDER BY ordering DESC LIMIT 1";
            $db =& JFactory::getDBO();
            $db->setQuery($query);
            $order =$db->loadObject();
            $row->ordering = $order->ordering + 1;
        }

        if (!$row->store())
        {
            echo "<script> alert('".$row->getError()."');
			window.history.go(-1); </script>\n";
            exit ();
        }

        switch($this->_task)
        {
            case 'apply':
                $link = 'index.php?option=com_djclassifieds&task=edit&cid[]='.$row->id;
                $msg = JText::_('CHANGES_SAVED');
                break;
            case 'save':
            default:
                $link = 'index.php?option=com_djclassifieds';
                $msg = JText::_('ITEM_SAVED');
                break;
        }


        /* Amol Start */
        $db =& JFactory::getDBO();
        $lang = & JFactory::GetLanguage();
        $modelEdit =& $this->getModel('Edit');
        $errArr = array();
        $hubLang = explode('-',$lang->getTag());
        //$hubLang = ($hubLangexplode[0])?$hubLangexplode[0]:'en';



        $childRegionIds = $modelEdit->getAllChildRegions($regionIds);
        // get all the regions including the child regions (No Duplicates)
        $allUniqueRegionIds = array_unique(array_merge($regionIds,$childRegionIds));
        $newSites = array();
        if(count($allUniqueRegionIds)) {
            foreach($allUniqueRegionIds as $uniqueRegionId) {
                $nsiteIds = $modelEdit->getSitesForRegionByLang($uniqueRegionId,$hubLang[0]);
                if(count($nsiteIds)) {
                    foreach($nsiteIds as $nsiteId) {
                        $newSites[]=$nsiteId;
                    }
                }
            }
        }

        $currSites = $newSites;
        $prevSites = $modelEdit->getPrevSitesForCat($row->id);
        //$catId = $row->cat_id;
        //$item, $catId, $currSites, $prevSites)
        $resArr = $this->_itemProp->propagateCategory($row, $currSites, $prevSites );

        if(count($resArr) == 0) {

            $modelEdit->deleteCatDataFromRegion($row->id);
            $modelEdit->deleteCatDataFromSite($row->id);

            $regionIds = array_unique($regionIds);
            foreach($regionIds as $regionId) {
                $modelEdit->addCatDataToRegion($row->id,$regionId);
            }

            $nsiteIds = array();
            foreach($allUniqueRegionIds as $uniqueRegionId) {
                $nsiteIds = array_merge($nsiteIds,$modelEdit->getSitesForRegionByLang($uniqueRegionId,$hubLang[0]));
            }
            if(count($nsiteIds)){
                $nsiteIds = array_unique($nsiteIds);
                foreach($nsiteIds as $nsiteId) {
                    $modelEdit->addCatDataToSite($row->id,$nsiteId);
                }
            }


        } else {
            $s = '';
            foreach ($resArr as $res) {
                $s.= $res['msg'].'<br />';
            }
            JError::raiseWarning(500,$s);
            $msg = 'Action not completely performed';
        }



        /* Amol End */
        $jApp->redirect($link, $msg);
    }


    function saveItem()

    {

        $jApp = &JFactory::getApplication();
        //$itemProp = new ItemPropagation();

        /* Amol Start*/
        $regionIds = JRequest::getVar('regionMulti', null, 'POST', 'array');
        if(!count($regionIds)) {
            echo "<script> alert('Select Region Id or Ids');
				window.history.go(-1); </script>\n";
            exit ();
        }
        /* Amol End*/


        $row = & JTable::getInstance('items', 'table');
        $par = &JComponentHelper::getParams( 'com_djclassifieds' );

        if (!$row->bind(JRequest::get('post')))
        {

            echo "<script> alert('".$row->getError()."');

			window.history.go(-1); </script>\n";

            exit ();

        }

        $row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $row->intro_desc = JRequest::getVar('intro_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $row->contact = nl2br(JRequest::getVar('contact', '', 'post', 'string'));

        //removing images from folder and from database
        $images = $row->image_url;
        $usun = @$_POST['usun'];
        $path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/";

        for ($i = 0; $i < count($usun); $i++)
        {

            $images = str_replace($usun[$i].';', '', $images);
            //deleting the main image
            if (JFile::exists($path_to_delete.$usun[$i]))
            {
                JFile::delete($path_to_delete.$usun[$i]);
            }
            //deleting thumbnail of image
            if (JFile::exists($path_to_delete.$usun[$i].'.thb.jpg'))
            {
                JFile::delete($path_to_delete.$usun[$i].'.thb.jpg');
            }
            if (JFile::exists($path_to_delete.$usun[$i].'.th.jpg'))
            {
                JFile::delete($path_to_delete.$usun[$i].'.th.jpg');
            }
            if (JFile::exists($path_to_delete.$usun[$i].'.thm.jpg'))
            {
                JFile::delete($path_to_delete.$usun[$i].'.thm.jpg');
            }

            //deleting icon of image
            if (JFile::exists($path_to_delete.$usun[$i].'.ths.jpg'))
            {
                JFile::delete($path_to_delete.$usun[$i].'.ths.jpg');
            }
        }


        //add images
        $pliki = $_FILES['image'];
        if(count($pliki['name'])>0 && $row->id==0){
            $query = "SELECT id FROM #__djcf_items ORDER BY id DESC LIMIT 1";
            $db =& JFactory::getDBO();
            $db->setQuery($query);
            $last_id =$db->loadResult();
            $last_id++;
        }else{
            $last_id= $row->id;
        }
        $nw = (int)$par->get('th_width',-1);
        $nh = (int)$par->get('th_height',-1);
        $nws = $par->get('smallth_width',-1);
        $nhs = $par->get('smallth_height',-1);
        $nwm = $par->get('middleth_width',-1);
        $nhm = $par->get('middleth_height',-1);
        $nwb = $par->get('bigth_width',-1);
        $nhb = $par->get('bigth_height',-1);


        for ($i = 0; $i < count($pliki['name']); $i++)
        {
            if (substr($pliki['type'][$i], 0, 5) == "image")
            {
                $n_name = $last_id.'_'.str_ireplace(' ', '_',$pliki['name'][$i]);
                $sciezka = JPATH_BASE."/../components/com_djclassifieds/images/".$n_name;
                $nimg= 0;
                while(JFile::exists($sciezka)){
                    $nimg++;
                    $n_name = $last_id.'_'.$nimg.'_'.$pliki['name'][$i];
                    $sciezka = JPATH_BASE."/../components/com_djclassifieds/images/".$n_name;
                }
                $images .= $n_name.';';
                move_uploaded_file($pliki['tmp_name'][$i], $sciezka);
                $this->makeimg($sciezka, $nw, $nh, 'th');

                $this->makeimg($sciezka, $nws, $nhs, 'ths');
                $this->makeimg($sciezka, $nwm, $nhm, 'thm');
                $this->makeimg($sciezka, $nwb, $nhb, 'thb');


            }
        }
        $row->image_url = $images;



        if($row->date_start=='0000-00-00' || $row->date_start==''){
            $date_time =& JFactory::getDate();
            $date_all=$date_time->toMySQL();
            $date = explode(' ',$date_all);
            $row->date_start=$date[0];
        }

        if($row->user_id=='' || ($row->user_id=='0' && $row->id=='' )){
            $user	=& JFactory::getUser();
            $row->user_id=$user->id;
            $row->payed=1;
        }


        if (!$row->store())
        {
            echo "<script> alert('".$row->getError()."');
			window.history.go(-1); </script>\n";
            exit ();
        }

        switch($this->_task)
        {
            case 'applyItem':
                $link = 'index.php?option=com_djclassifieds&task=editItem&cid[]='.$row->id;
                $msg = JText::_('CHANGES_SAVED');
                break;
            case 'saveItem':
            default:
                $link = 'index.php?option=com_djclassifieds&task=items&cat_id='.$row->cat_id;
                $msg = JText::_('ITEM_SAVED');
                break;
        }

        /* Amol Start */
        $db =& JFactory::getDBO();
        $lang = & JFactory::GetLanguage();
        $modelEdit =& $this->getModel('Edit');
        $errArr = array();
        $hubLang = explode('-',$lang->getTag());
        //$hubLang = ($hubLangexplode[0])?$hubLangexplode[0]:'en';



        $childRegionIds = $modelEdit->getAllChildRegions($regionIds);
        // get all the regions including the child regions (No Duplicates)
        $allUniqueRegionIds = array_unique(array_merge($regionIds,$childRegionIds));

        $newSites = array();
        if(count($allUniqueRegionIds)) {
            foreach($allUniqueRegionIds as $uniqueRegionId) {
                $nsiteIds = $modelEdit->getSitesForRegionByLang($uniqueRegionId,$hubLang[0]);
                if(count($nsiteIds)) {
                    foreach($nsiteIds as $nsiteId) {
                        $newSites[]=$nsiteId;
                    }
                }
            }
        }
        $newSites = array_unique($newSites);

        $currSites = $newSites;
        $prevSites = $modelEdit->getPrevSitesForAd($row->id);
        $catId = $row->cat_id;

        //$item, $catId, $currSites, $prevSites)
        $resArr = $this->_itemProp->propagateItem($row, $currSites, $prevSites );

        if(count($resArr) == 0) {

            $modelEdit->deleteAdDataFromRegion($row->id);
            $modelEdit->deleteAdDataFromSite($row->id);

            foreach($regionIds as $regionId) {
                $modelEdit->addAdDataToRegion($row->id,$regionId);
            }

            foreach($allUniqueRegionIds as $uniqueRegionId) {
                $nsiteIds = $modelEdit->getSitesForRegionByLang($uniqueRegionId,$hubLang[0]);
                foreach($nsiteIds as $nsiteId) {
                    $modelEdit->addAdDataToSite($row->id,$nsiteId);
                }
            }


        } else {
            $s = '';
            foreach ($resArr as $res) {
                $s.= $res['msg'].'<br />';
            }
            JError::raiseWarning(500,$s);
            $msg = 'Action not completely performed';
        }



        /* Amol End */
        $jApp->redirect($link, $msg);
    }

    function publish()
    {
        $option = JRequest::getVar('option','com_djclassifieds');
        $task = JRequest::getVar('t', 'post');
        $cid = JRequest::getVar('cid', array (), '', 'array');

        if ($task == "items" or $task == "nocategoryitems")
        {
            if ($this->_task == 'publish')
            {
                $publish = 1;
            }
            else
            {
                $publish = 0;
            }
            $nlTable = & JTable::getInstance('items', 'table');
            $nlTable->publish($cid, $publish);
            $limit = JRequest::getVar('limit', 10, '', 'int');
            $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
            $order = JRequest::getVar('order');
            $ord_t = JRequest::getVar('ord_t');
            $cat_id = JRequest::getVar('cat_id',0,'',int);

            $redirUrl = 'index.php?option=com_djclassifieds&task='.$task.'&cat_id='.$cat_id.'&order='.$order.'&ord_t='.$ord_t.'&limit='.$limit.'&limitstart='.$limitstart; //amol
            $tableName = '#__djcf_items';//amol
            /*$this->setRedirect('index.php?option=com_djclassifieds&task='.$task.'&cat_id='.$cat_id.'&order='.$order.'&ord_t='.$ord_t.'&limit='.$limit.'&limitstart='.$limitstart);*/
        }
        else
        {
            if ($this->_task == 'publish')
            {
                $publish = 1;
            }
            else
            {
                $publish = 0;
            }
            $nlTable = & JTable::getInstance('categories', 'table');
            $tableName = '#__djcf_categories'; //amol
            $nlTable->publish($cid, $publish);
            $redirUrl = 'index.php?option=com_djclassifieds'; //amol
            //$this->setRedirect('index.php?option=com_djclassifieds');
        }

        /* Amol Start */
        $msg = '';
        $modelEdit =& $this->getModel('Edit');

        if($tableName == '#__djcf_items' ) {
            foreach ($cid as $wid) {
                $prevSites = array();
                $currSites = $modelEdit->getPrevSitesForAd($wid); //in this case its currentsites
                $nlTable->load($wid);
                $resArr = $this->_itemProp->propagateItem($nlTable, $currSites, $prevSites );

                if(count($resArr) != 0) {
                    $revertVal = ($publish)?0:1;
                    $nlTable->publish($wid, $revertVal);
                    $s = '';
                    foreach ($resArr as $res) {
                        $s.= $res['msg'].'<br />';
                    }
                    JError::raiseWarning(500,$s);
                    $msg = 'Action not completely performed';
                }

            }

        }
        if($tableName == '#__djcf_categories' ) {
            foreach ($cid as $wid) {
                $prevSites = array();
                $currSites = $modelEdit->getPrevSitesForCat($wid); //in this case its currentsites
                $nlTable->load($wid);
                $resArr = $this->_itemProp->propagateCategory($nlTable, $currSites, $prevSites );
                // $sendParm = $resArr['msg'];

                if(count($resArr) != 0) {
                    $revertVal = ($publish)?0:1;
                    $nlTable->publish($wid, $revertVal);
                    $s = '';
                    foreach ($resArr as $res) {
                        $s.= $res['msg'].'<br />';
                    }
                    JError::raiseWarning(500,$s);
                    $msg = 'Action not completely performed';
                }
            }
        }




        /* Amol End */
        $this->setRedirect($redirUrl,$msg);

    }


    function special(){
        $jApp = &JFactory::getApplication();
        $option = JRequest::getVar('option','com_djclassifieds');
        $cid = JRequest::getVar( 'cid', array(), '', 'array');
        $cat_id = JRequest::getVar('cat_id');
        $row = & JTable::getInstance('items','table');
        $row->load( (int) $cid[0] );
        $origVal = $row->special; //Amol
        if($row->special=='1'){
            $row->special=0;
        }
        else{
            $row->special=1;
        }
        if(!$row->store())
        {
            echo "<script> alert('".$row->getError()."');
			window.history.go(-1); </script>\n";
            exit();
        }
        /* Amol Start */
        $prevSites = array();
        $wid = (int) $cid[0];
        $modelEdit =& $this->getModel('Edit');
        $currSites = $modelEdit->getPrevSitesForAd($wid); //in this case its currentsites
        $row->load($wid);
        $resArr = $this->_itemProp->propagateItem($row, $currSites, $prevSites );

        if(count($resArr) != 0) {
            $row->special = $origVal;
            if(!$row->store())
            {
                echo "<script> alert('".$row->getError()."');
			        window.history.go(-1); </script>\n";
                exit();
            }
            $s = '';
            foreach ($resArr as $res) {
                $s.= $res['msg'].'<br />';
            }
            JError::raiseWarning(500,$s);
            $msg = 'Action not completely performed';

        }
        /* Amol Start */

        $jApp->redirect('index.php?option=com_djclassifieds&task=items&cat_id='.$cat_id,$msg);
    }


    function cancel()
    {
        $jApp = &JFactory::getApplication();
        $jApp->redirect('index.php?option=com_djclassifieds');
    }

    function cancelItem()
    {
        $jApp = &JFactory::getApplication();
        $jApp->redirect('index.php?option=com_djclassifieds&task=items');

    }
    function recreateThumbnails(){
        $task = JRequest::getVar('t', 'post');
        $par = &JComponentHelper::getParams( 'com_djclassifieds' );
        $jApp = &JFactory::getApplication();
        $cid = JRequest::getVar('cid', array (), '', 'array');
        $db = & JFactory::getDBO();
        if (count($cid))
        {
            $cids = implode(',', $cid);
            $query = "SELECT id,image_url FROM #__djcf_items WHERE id IN ( ".$cids." )";
            $db->setQuery($query);
            $items = $db->loadObjectList();
            $sciezka = JPATH_BASE."/../components/com_djclassifieds/images/";
            $nw = (int)$par->get('th_width',-1);
            $nh = (int)$par->get('th_height',-1);
            $nws = $par->get('smallth_width',-1);
            $nhs = $par->get('smallth_height',-1);
            $nwm = $par->get('middleth_width',-1);
            $nhm = $par->get('middleth_height',-1);
            $nwb = $par->get('bigth_width',-1);
            $nhb = $par->get('bigth_height',-1);


            foreach($items as $i){
                if($i->image_url){
                    $images = explode(";",$i->image_url);
                    for($ii=0; $ii<count($images)-1;$ii++ ){
                        if (JFile::exists($sciezka.$images[$ii].'.thb.jpg')){
                            JFile::delete($sciezka.$images[$ii].'.thb.jpg');
                        }
                        if (JFile::exists($sciezka.$images[$ii].'.th.jpg')){
                            JFile::delete($sciezka.$images[$ii].'.th.jpg');
                        }
                        if (JFile::exists($sciezka.$images[$ii].'.thm.jpg')){
                            JFile::delete($sciezka.$images[$ii].'.thm.jpg');
                        }
                        if (JFile::exists($sciezka.$images[$ii].'.ths.jpg')){
                            JFile::delete($sciezka.$images[$ii].'.ths.jpg');
                        }

                        $this->makeimg($sciezka.$images[$ii], $nw, $nh, 'th');
                        $this->makeimg($sciezka.$images[$ii], $nws, $nhs, 'ths');
                        $this->makeimg($sciezka.$images[$ii], $nwm, $nhm, 'thm');
                        $this->makeimg($sciezka.$images[$ii], $nwb, $nhb, 'thb');
                    }
                }
            }


        }
        $limit = JRequest::getVar('limit', 10, '', 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $order = JRequest::getVar('order');
        $cat_id = JRequest::getVar('cat_id',0,'',int);
        $redirect = 'index.php?option=com_djclassifieds&task='.$task.'&cat_id='.$cat_id.'&order='.$order.'&limit='.$limit.'&limitstart='.$limitstart;
        $jApp->redirect($redirect, JText::_('THUMBNAILS_RECREATED'));
    }

    function remove()
    {
        $jApp = &JFactory::getApplication();
        $cid = JRequest::getVar('cid', array (), '', 'array');
        $db = & JFactory::getDBO();



        if (count($cid))
        {

            /* Amol Start */
            // check for child items for category, if found return
            foreach ($cid as $wid) {
                $db->setQuery("SELECT * FROM #__djcf_categories WHERE parent_id=".$wid);
                $db->query();

                if($db->getNumRows()>0) {
                    JError::raiseWarning(500,"Cannot delete category since contains child categories");
                    $link = 'index.php?option=com_djclassifieds';
                    $jApp->redirect($link);
                }

                $db->setQuery("SELECT * FROM #__djcf_items WHERE cat_id=".$wid);
                $db->query();

                if($db->getNumRows()>0) {
                    JError::raiseWarning(500,"Cannot delete category since contains Ad Items");
                    $link = 'index.php?option=com_djclassifieds';
                    $jApp->redirect($link);
                }


            }

            $cids = implode(',', $cid);
            $query = "DELETE FROM #__djcf_categories WHERE ID IN ( ".$cids." )";
            $db->setQuery($query);
            if (!$db->query())
            {
                echo "<script alert('".$db->getErrorMsg()."');
				window.history.go(-1); </script>\n";
            }
            /* Amol End */


            /*
             DJ CODE
             $cids = implode(',', $cid);
             $query = "DELETE FROM #__djcf_categories WHERE ID IN ( ".$cids." )";
             $db->setQuery($query);
             if (!$db->query())
             {
             echo "<script alert('".$db->getErrorMsg()."');
             window.history.go(-1); </script>\n";
             }
             $query = "DELETE FROM #__djcf_categories WHERE parent_id IN ( ".$cids." )";
             $db->setQuery($query);
             if (!$db->query())
             {
             echo "<script alert('".$db->getErrorMsg()."');
             window.history.go(-1); </script>\n";
             }
             */


            $msg='<ol><li>Sucessfully deleted</li>';
            $modelEdit =& $this->getModel('Edit');
            $good_array = array();
            $bad_array = array();

            foreach ($cid as $wid) {
                $sites = $modelEdit->getPrevSitesForCat($wid);
                if(count($sites)) {
                    $sites_err = $this->_itemProp->deleteCategory($wid,$sites);

                    if (count($sites_err)) {
                        if (!$result['success']) {
                            $bad_array[] = $wid;
                        } else {
                            $good_array[] = $wid;
                        }
                    } else {
                        // no error sites
                        $good_array[] = $wid;
                    }

                }
                else {
                    $good_array[] = $wid;
                }

            }



            foreach ($good_array as $id) {
                $modelEdit->deleteCatDataFromRegion($id);
                $modelEdit->deleteCatDataFromSite($id);
            }

            if(count($good_array)) {
                $msg.='<li>Cats deleted Successfully :: '.implode(',',$good_array).'</li>';
            }
            if(count($bad_array)) {
                $msg.='<li>cats Having Problem :: '.implode(',',$bad_array).'</li>';
            }

            $msg.='</ol>';
            /* Amol End */

        }
        $jApp->redirect('index.php?option=com_djclassifieds', $msg);
    }

    function removeItem()
    {
        $task = JRequest::getVar('t', 'post');
        $jApp = &JFactory::getApplication();
        $cid = JRequest::getVar('cid', array (), '', 'array');
        $db = & JFactory::getDBO();
        if (count($cid))
        {
            $cids = implode(',', $cid);
            $query = "SELECT id,image_url FROM #__djcf_items WHERE id IN ( ".$cids." )";
            $db->setQuery($query);
            $items = $db->loadObjectList();
            $path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/";

            foreach($items as $i){
                if($i->image_url){
                    $images = explode(";",$i->image_url);
                    for($ii=0; $ii<count($images)-1;$ii++ ){
                        if (JFile::exists($path_to_delete.$images[$ii])){
                            JFile::delete($path_to_delete.$images[$ii]);
                        }
                        if (JFile::exists($path_to_delete.$images[$ii].'.thb.jpg')){
                            JFile::delete($path_to_delete.$images[$ii].'.thb.jpg');
                        }
                        if (JFile::exists($path_to_delete.$images[$ii].'.th.jpg')){
                            JFile::delete($path_to_delete.$images[$ii].'.th.jpg');
                        }
                        if (JFile::exists($path_to_delete.$images[$ii].'.thm.jpg')){
                            JFile::delete($path_to_delete.$images[$ii].'.thm.jpg');
                        }
                        if (JFile::exists($path_to_delete.$images[$ii].'.ths.jpg')){
                            JFile::delete($path_to_delete.$images[$ii].'.ths.jpg');
                        }
                    }
                }
            }

            $query = "DELETE FROM #__djcf_items WHERE id IN ( ".$cids." )";
            $db->setQuery($query);
            if (!$db->query())
            {
                echo "<script alert('".$db->getErrorMsg()."');
				window.history.go(-1); </script>\n";
            }
            $msg='<ol><li>Sucessfully deleted</li>';


            /* Amol Start */
            $modelEdit =& $this->getModel('Edit');
            $good_array = array();
            $bad_array = array();

            foreach ($cid as $wid) {
                $sites = $modelEdit->getPrevSitesForAd($wid);
                if(count($sites)) {
                    $result = $this->_itemProp->deleteItem($wid,$sites);

                    if (count($result)) {
                        if (!$result['success']) {
                            $bad_array[] = $wid;
                        } else {
                            $good_array[] = $wid;
                        }
                    } else {
                        $good_array[] = $wid;
                    }

                }
                else {
                    $good_array[] = $wid;
                }

            }



            foreach ($good_array as $id) {
                $modelEdit->deleteAdDataFromRegion($id);
                $modelEdit->deleteAdDataFromSite($id);
            }

            if(count($good_array)) {
                $msg.='<li>Items deleted Successfully :: '.implode(',',$good_array).'</li>';
            }
            if(count($bad_array)) {
                $msg.='<li>Items Having Problem :: '.implode(',',$bad_array).'</li>';
            }

            $msg.='</ol>';


            /* Amol End */

        }
        $limit = JRequest::getVar('limit', 10, '', 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $order = JRequest::getVar('order');
        $cat_id = JRequest::getVar('cat_id',0,'','int');
        $redirect = 'index.php?option=com_djclassifieds&task='.$task.'&cat_id='.$cat_id.'&order='.$order.'&limit='.$limit.'&limitstart='.$limitstart;
        $jApp->redirect($redirect, $msg);
    }


    function orderup(){
        $jApp = &JFactory::getApplication();

        $db		= & JFactory::getDBO();

        $cid = JRequest::getVar( 'cid', array(), '', 'array');
        $cat_id = JRequest::getVar( 'filter_catid', '', '', 'int');
        $ord = JRequest::getVar('order');


        if (isset( $cid[0] ))
        {
            $row = & JTable::getInstance('categories','table');
            $row->load( (int) $cid[0] );
            /* Hyperlocalizer start */
            $rowIDsChanged = $row->getIDsThatChangeOnMove(-1, 'parent_id = ' . (int) $row->parent_id );
            $result = $row->move(-1, 'parent_id = ' . (int) $row->parent_id );
            if ($result) {
                $modelEdit =& $this->getModel('Edit');
                $s = '';
                foreach ($rowIDsChanged as $rowId) {
                    $prevSites = $modelEdit->getPrevSitesForCat($rowId);
                    $row = & JTable::getInstance('categories','table');
                    $row->load( $rowId );
                    $resArr = $this->_itemProp->propagateCategory($row, $prevSites, $prevSites );
                    if (count($resArr) != 0) {
                        foreach ($resArr as $res) {
                            $s .= $res['msg'].'<br />';
                        }
                    }
                }
                if ($s) {
                    JError::raiseWarning(500,$s);
                }
            }
        }
        $jApp->redirect('index.php?option=com_djclassifieds&task=categories&filter_catid='.$cat_id);
    }

    function orderdown(){
        $jApp = &JFactory::getApplication();

        $db		= & JFactory::getDBO();

        $cid = JRequest::getVar( 'cid', array(), '', 'array');
        $cat_id = JRequest::getVar( 'filter_catid', '', '', 'int');
        $ord = JRequest::getVar('order');

        if (isset( $cid[0] ))
        {
            $row = & JTable::getInstance('categories','table');
            $row->load( (int) $cid[0] );
            /* Hyperlocalizer start */
            $rowIDsChanged = $row->getIDsThatChangeOnMove(1, 'parent_id = ' . (int) $row->parent_id );
            $result = $row->move(1, 'parent_id = ' . (int) $row->parent_id);
            if ($result) {
                $modelEdit =& $this->getModel('Edit');
                $s = '';
                foreach ($rowIDsChanged as $rowId) {
                    $prevSites = $modelEdit->getPrevSitesForCat($rowId);
                    $row = & JTable::getInstance('categories','table');
                    $row->load( $rowId );
                    $resArr = $this->_itemProp->propagateCategory($row, $prevSites, $prevSites );
                    if (count($resArr) != 0) {
                        foreach ($resArr as $res) {
                            $s .= $res['msg'].'<br />';
                        }
                    }
                }
                if ($s) {
                    JError::raiseWarning(500,$s);
                }
            }
        }
        $jApp->redirect('index.php?option=com_djclassifieds&task=categories&filter_catid='.$cat_id);
    }


    function saveOrder()
    {
        $jApp = &JFactory::getApplication();

        $db			= & JFactory::getDBO();
        $cid = JRequest::getVar( 'cid', array(), '', 'array');
        $order = JRequest::getVar( 'order', array (), '', 'array' );
        $cat_id = JRequest::getVar( 'filter_catid', '', '', 'int');
        $ord = JRequest::getVar('order');
        $total		= count($cid);
        $conditions	= array ();

        $row = & JTable::getInstance('categories','table');

        for ($i = 0; $i < $total; $i ++)
        {
            $row->load( (int) $cid[$i] );
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    JError::raiseError( 500, $db->getErrorMsg() );
                    return false;
                }
                $condition = 'parent_id = ' . (int) $row->parent_id;
                $found = false;
                foreach ($conditions as $cond)
                if ($cond[1] == $condition) {
                    $found = true;
                    break;
                }
                if (!$found)
                $conditions[] = array ($row->id, $condition);
            }
        }

        foreach ($conditions as $cond)
        {
            $row->load($cond[0]);
            $row->reorder($cond[1]);
        }
        $jApp->redirect('index.php?option=com_djclassifieds&task=categories&filter_catid='.$cat_id);
    }

    function makeimg($adres, $nw, $nh, $ext)
    {
        $par = &JComponentHelper::getParams( 'com_djclassifieds' );

        /* ------------------- */
        if (!$adres)
        return false;

        if (! list ($w, $h, $type, $attr) = getimagesize($adres)) {
            if (! list ($w, $h, $type, $attr) = getimagesize($adres)) {
                return false;
            }
        }

        switch($type)
        {
            case 1:
                $simg = imagecreatefromgif($adres);
                break;
            case 2:
                $simg = imagecreatefromjpeg($adres);
                break;
            case 3:
                $simg = imagecreatefrompng($adres);
                break;
        }

        $x = 0;
        $y = 0;
        $cw = $w;
        $ch = $h;

        $nw_half = (int)floor($nw/2);
        $nh_half = (int)floor($nh/2);
        $w_half = (int)floor($w/2);
        $h_half = (int)floor($h/2);

        if ($nw == 0 && $nh == 0) {
            $nw = 200;
            $nh = (int)(floor(($nw * $h) / $w));
        }
        elseif ($nw == 0) {
            $nw = (int)(floor(($nh * $w) / $h));
        }
        elseif ($nh == 0) {
            $nh = (int)(floor(($nw * $h) / $w));
        }
        elseif ($nw < $w || $nh < $h) {
            if ($nw <= $nh)
            {
                if ($w <= $h) {
                    $ch = $h;
                    $temp_w = (int)floor(($h * $nw)/$nh);
                    if ($temp_w > $w) {
                        $cw = $w;
                    }
                    else {
                        $cw = $temp_w;
                        $x = $w_half - (int)($temp_w/2);
                    }
                }
                elseif ($w > $h) {
                    $ch = $h;
                    $temp_w = (int)floor(($h * $nw)/$nh);
                    if ($temp_w > $w) {
                        $cw = $w;
                    }
                    else {
                        $cw = $temp_w;
                        $x = $w_half - (int)($temp_w/2);
                    }
                }
            }
            elseif ($nw > $nh) {
                if ($w <= $h) {
                    $cw = $w;
                    $temp_h = (int)floor(($nh * $w)/$nw);
                    if ($temp_h > $h) {
                        $ch = $h;
                    }
                    else {
                        $ch = $temp_h;
                        $y = $h_half - (int)($temp_h/2);
                    }
                }
                elseif ($w > $h) {
                    $cw = $w;
                    $temp_h = (int)floor(($nh * $w)/$nw);
                    if ($temp_h > $h) {
                        $ch = $h;
                    }
                    else {
                        $ch = $temp_h;
                        $y = $h_half - (int)($temp_h/2);
                    }
                }
            }
        }
        elseif ($nw == -1 || $nh == -1) {
            return false;
        }
        $dimg = imagecreatetruecolor($nw, $nh);
        $bgColor = imagecolorallocate($dimg, 255, 255, 255);
        imagefill($dimg, 0, 0, $bgColor);
        imagecopyresampled($dimg, $simg, 0, 0, $x, $y, $nw, $nh, $cw, $ch);

        $thumb_path = $adres.'.'.$ext.'.jpg';
        if (is_file($thumb_path))
        unlink($thumb_path);

        imagejpeg($dimg, $thumb_path, 85);

        return true;
    }



}
