<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldArticles extends JFormField
{
    protected $type = 'Articles';

	function getInput()
	{
		JHtml::_('behavior.modal', 'a.modal');

		$script = array();
		$script[] = '	function jSelectArticle_'.$this->id.'(id, title, catid, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		$html	= array();
		$link	= 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle_'.$this->id;
               
		$db	= JFactory::getDBO();
		
        $db->setQuery(
			'SELECT article_id' .
			' FROM #__enmasse_setting'
		);
		$article_id = $db->loadResult();
        if($article_id){
            $this->value = $article_id;
        }
        $db->setQuery(
			'SELECT title' .
			' FROM #__content' .
			' WHERE id = '.(int)$this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
		}

		if (empty($title)) {
			$title = JText::_('COM_CONTENT_SELECT_AN_ARTICLE');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" size="35" />';
		$html[] = '</div>';

		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '	<a class="modal" title="'.JText::_('COM_CONTENT_CHANGE_ARTICLE').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_CONTENT_CHANGE_ARTICLE_BUTTON').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';

		$value = (int)$this->value;		

		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'_id" value="'.$value.'" />';

		return implode("\n", $html);                                
	}
}
