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
class TreeNodeHelper{
var $parent_id;
var $id;
var $name;
var $price;
var $description;
var $ordering;
var $childs = Array();
var $level;
var $published;
var $autopublish;
var $checked;


function __construct(){
$parent_id=null;
$id=null;
$name=null;
$description=null;
$ordering=null;
$price=null;
$childs[]=null;
$elem[]=null;
$level=0;
$published=null;
$autopublish=null;
$checked=null;
}



public function getSortList(& $lists,& $lists_const,& $ii, & $pagination ,& $obj=Array(),& $sprawdzone=';'){
	$liczba = count($lists_const);
	foreach($lists as $list){

			if(strstr($sprawdzone,';'.$list->id.';')){
				$flaga=1;
			}else{
				$flaga=0;
			}
			
			if($flaga==0){
			$sprawdzone.=$list->id.';';
			
			if(!isset($list->checked)){
				$list->checked = '';
			} 
			if(!isset($list->level)){
				$list->level ='';
			}
			//print_r($obj);
			$this->parent_id = $list->parent_id;
			$this->id = $list->id;
			$this->name = $list->name;
			$this->price = $list->price;	
			$this->description = $list->description;		
			$this->checked = $list->checked;
			$this->ordering = $list->ordering;
			//print_r($list);
			$this->autopublish = $list->autopublish;
			$this->published = $list->published;		
			$obj[]=$this;
			$this->name = JHTML::link('index.php?option=com_djclassifieds&task=edit&cid[]='.$this->id, $this->name);
			
			$checked = JHTML::_('grid.id', $ii, $list->id );
			$published=JHTML::_('grid.published', $list, $ii);


			?><tr>
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $this->id; ?>
            </td>
            <td>
                <?php echo $this->name; ?>
            </td>
			<td>
				<?php
					if(strlen($this->description) > 130){
					   echo mb_substr($this->description, 0, 130,'utf-8').' ...';						
					}else{
						echo $this->description;
					}	
				?>
			</td>
			<td class="order">
				<?php
				if(JRequest::getVar('filter_catid') != 0){
					$ordering=true;
				}else{
					$ordering=false;
				}
				?>
				<span><?php echo $pagination->orderUpIcon( $ii, true,'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $pagination->orderDownIcon( $ii, count($lists), true, 'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $this->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
			</td>
            <td>
            	<?php echo $this->parent_id;?>
            </td>
            <td>
                <?php echo $this->price/100; ?>
            </td>	
			 <td align="center">
                <?php
				if($this->autopublish == '0'){
					echo JText::_('Global');
				}elseif($this->autopublish == '1'){
					echo JText::_('Yes');
				}elseif($this->autopublish == '2'){
					echo JText::_('No');
				}
				?>
            </td>		
            <td align="center">
                <?php echo $published; ?>
            </td>
        </tr>
			<?php
			$ii++;			
				$this->childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new TreeNodeHelper();
						$child->id=$lists_const[$i]->id;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->level=$list->level+1;
						$child->price = $lists_const[$i]->price;
						$child->description = $lists_const[$i]->description;
						$child->ordering = $lists_const[$i]->ordering;						
						$child->published=$lists_const[$i]->published;
						$child->autopublish=$lists_const[$i]->autopublish;
						$new_name=$lists_const[$i]->name;
							for($lev=0;$lev<$child->level;$lev++){
								$new_name="&nbsp;&nbsp;&nbsp;&nbsp;".$new_name;
							}
								
						$child->name=$new_name;

						
						$this->childs[]=$child;
						$this->getSortList($this->childs,$lists_const,$ii,$pagination,$obj,$sprawdzone);
					
					}
				}	
				
			}
	}
				
	return(null);		
}



}

?>

