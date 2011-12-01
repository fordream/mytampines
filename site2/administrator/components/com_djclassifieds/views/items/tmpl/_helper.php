<?php
/**
* @version		0.9.1
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
//error_reporting(E_ALL);
class TreeNodeHelper{
var $parent_id;
var $last;
var $id;
var $name;
var $childs = Array();
var $level;

function __construct(){
$parent_id=null;
$last=0;
$id=null;
$name=null;
$childs[]=null;
$elem[]=null;
$level=0;
}




public function getTreeList(& $lists_const){
$cat = new TreeNodeHelper();
$main_cat = $cat->getMainCategories();

$cid = JRequest::getVar('cat_id', 0, '', 'int');
$id= $cat->getCategory($cid);
if($id){
	$main_id=& $cat->getMainParent($id, $list);
	$all_parent=& $cat->getChildsParents($id, $lists_const);	
}else{
	$main_id->id = '';
	$all_parent='';
}

$limit = JRequest::getVar('limit', 10, '', 'int');
$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

$order=JRequest::getVar('order', 'ordering');

foreach($main_cat as $cat){

	if($cat->id==$main_id->id){
	$cat = new TreeNodeHelper();
	$cat->getSortList($main_id,$lists_const,$id, $all_parent);			
	}
	else{
		$name = JHTML::link('index.php?option=com_djclassifieds&task=items&cat_id='.$cat->id.'&limit='.$limit.'&limitstart='.$limitstart.'&order='.$order, $cat->name);
		?><li class="level0"><?php
		echo ($name);
		?></li><?php
	}
}

}


public function getSortList(& $list,& $lists_const,& $main_id,& $all_parent,& $sprawdzone=Array(), & $ii=0){

	$liczba = count($lists_const);
	$li_parent= count($all_parent);



				$flaga=0;
				for($l=0;$l<count($sprawdzone);$l++){
					if($sprawdzone[$l]==$list->id){
						$flaga=1;
					}
				}
			
			if($flaga==0){
			$sprawdzone[]=$list->id;
			$list->level='';						

			$this->parent_id = $list->parent_id;
			$this->id = $list->id;
			$this->name = $list->name;
			$this->level= $list->level;
			$limit		= JRequest::getVar('limit', 0, '', 'int');
			$limitstart	= 0;
			$order=JRequest::getVar('order', 'ordering');
			$this->name = JHTML::link('index.php?option=com_djclassifieds&task=items&cat_id='.$list->id.'&limit='.$limit.'&limitstart='.$limitstart.'&order='.$order, $this->name);
			$checked = JHTML::_('grid.id', ++$ii, $this->id );
			$published=JHTML::_('grid.published', $list, $ii);
			$ii++;
			?>
    <li class="level0">
        <?php echo $this->name; ?>
    </li>
<?php
			
			
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$flaga=0;
						for($j=0;$j<$li_parent;$j++){
							if($lists_const[$i]->id==$all_parent[$j]){
								$flaga=1;
							}
						}
						if($flaga==1){
						$child=new TreeNodeHelper();
						$child->id=$lists_const[$i]->id;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->level=$list->level+1;
						$child->grant_id=$list->id;
						$new_name=$lists_const[$i]->name;/*
							for($lev=0;$lev<$child->level;$lev++){
								$new_name="&nbsp;&nbsp;&nbsp;&nbsp;".$new_name;
							}*/
								
						$child->name=$new_name;

							$this->childs[]=$child;						
							
							
							$this->getList($this->childs,$lists_const,$main_id,$all_parent,$sprawdzone,$ii);
							
					}}
				}	
				
			}

	return(null);		
}


public function getList(& $lists,& $lists_const,& $main_id,& $all_parent,& $sprawdzone=Array(),& $ii){

	$liczba = count($lists_const);
		$li_parent= count($all_parent);
	foreach($lists as $list){

				$flaga=0;
				for($l=0;$l<count($sprawdzone);$l++){
					if($sprawdzone[$l]==$list->id){
						$flaga=1;
					}
				}
			
			if($flaga==0){
			$sprawdzone[]=$list->id;

			$this->parent_id = $list->parent_id;
			$this->id = $list->id;
			$this->name = $list->name;
			$this->level= $list->level;
			$limit		= JRequest::getVar('limit', 0, '', 'int');
			$limitstart	= 0;
			$order=JRequest::getVar('order', 'ordering');
			
			$this->name = JHTML::link('index.php?option=com_djclassifieds&task=items&cat_id='.$list->id.'&limit='.$limit.'&limitstart='.$limitstart.'&order='.$order, $this->name);
			//$checked = JHTML::_('grid.id', ++$ii, $this->id );
			//$published=JHTML::_('grid.published', $this, $ii);
			$ii++;

			?>

    <li class="level<?php echo $this->level; ?>" >
        <?php echo $this->name; ?>
    </li>
<?php
			
			
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){	
						$flaga=0;

						for($j=0;$j<$li_parent;$j++){
							if($lists_const[$i]->id==$all_parent[$j]){
								$flaga=1;
							}
						}
					
						if($flaga==1){
						$child=new TreeNodeHelper();
//							echo($lists_const[$i]->id);
						$child->id=$lists_const[$i]->id;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->level=$list->level+1;
						$child->grant_id=$list->id;
						$new_name=$lists_const[$i]->name;
						/*	for($lev=0;$lev<$child->level;$lev++){
								$new_name="&nbsp;&nbsp;&nbsp;&nbsp;".$new_name;
							}*/
								
						$child->name=$new_name;

			
						if($list->parent_id!=$main_id->id){
							$this->childs[]=$child;														
							$this->getList($this->childs,$lists_const,$main_id,$all_parent,$sprawdzone,$ii);
							}

					}}
				}	
				
			}
	}	
	return(null);		
}


function getMainParent(& $id, & $list){

	if($id->parent_id==0){
		$list = $id;
	}
	else{
		$new_id = $id->parent_id;
		$obj = new TreeNodeHelper();
		$new_id_ob=$obj->getCategory($new_id);
		$obj->getMainParent($new_id_ob,$list);
	}

		return $list;	
}

function getChildsParents(& $id,& $lists){
	$child_list=Array();
	$id2='';
	if($id->id){
		$id2=$id->id;
	}
	foreach($lists as $list){
		if($list->parent_id==$id2){
			$child_list[]=$list->id;
		}
	}
	$all_list=Array();
	$parent_list=Array();
	$obj = new TreeNodeHelper();
	$parent_list=$obj->getAllParent($id, $all_list,$lists);
	$all_list=array_merge_recursive ($parent_list,$child_list);
	return($all_list);
}

function getAllParent(& $id, & $all_list=Array(),& $lists){

	if($id->parent_id==0){
		//$all_list[] = $id->id;
		
		

	}
	else{
		$new_id = $id->parent_id;
		$obj = new TreeNodeHelper();
		$new_id_ob=$obj->getCategory($new_id);
		$siblings=$obj->getParent($id,$lists);
//		print_r($siblings);
		$all_list[]=$id->id;
		$all_list=array_merge_recursive ($all_list,$siblings);
		$obj->getAllParent($new_id_ob,$all_list, $lists);
	}
		
		
		return $all_list;	
}

function getParent(& $id, & $lists){
	$result=Array();
	foreach($lists as $list){
		if($list->parent_id==$id->parent_id){
			$result[]=$list->id;
		}
	}
	return $result;
}

public function getCategory($id){

		$db= &JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_categories WHERE id =".$id;
		$db->setQuery($query);
		$category=$db->loadObject();

		return $category;
	
}

public function getMainCategories(){
		
		$db= &JFactory::getDBO();
			$query = "SELECT * FROM #__djcf_categories WHERE parent_id=0";
			$db->setQuery($query);
			$main_categories=$db->loadObjectList();
		

		return $main_categories;
	}

}

class DjclassifiedsModelCategories{
	
	var $_categories;
	
	function getCategories(){
		
		$db= &JFactory::getDBO();
			$query = "SELECT * FROM #__djcf_categories";
			$db->setQuery($query);
			$_categories=$db->loadObjectList();
		

		return $_categories;
	}
}
?>
