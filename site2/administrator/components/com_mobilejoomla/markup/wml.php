<?php
/**
 * Mobile Joomla!
 * http://www.mobilejoomla.com
 *
 * @version		1.0 RC3
 * @license		http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL
 * @copyright	(C) 2008-2011 MobileJoomla!
 * @date		September 2011
 */
defined('_JEXEC') or die('Restricted access');

class MobileJoomla_WML extends MobileJoomla
{
	function getMarkup()
	{
		return 'wml';
	}

	function getCharset()
	{
		return 'utf-8';
	}

	function getContentType()
	{
		return 'text/vnd.wap.wml';
	}

	function getContentString()
	{
		return 'text/vnd.wap.wml; charset=utf-8';
	}

	function showXMLheader()
	{
	}

	function showDocType()
	{
	}

	function getXmlnsString()
	{
		return '';
	}

	function showHead($showstylesheet = true)
	{
	}

	function getPosition($pos)
	{
		if(!isset($this->config)) return '';
		switch($pos)
		{
			case 'header':
				return $this->config['tmpl_wap_header1'];
			case 'header2':
				return $this->config['tmpl_wap_header2'];
			case 'header3':
				return $this->config['tmpl_wap_header3'];
			case 'middle':
				return $this->config['tmpl_wap_middle1'];
			case 'middle2':
				return $this->config['tmpl_wap_middle2'];
			case 'middle3':
				return $this->config['tmpl_wap_middle3'];
			case 'footer':
				return $this->config['tmpl_wap_footer1'];
			case 'footer2':
				return $this->config['tmpl_wap_footer2'];
			case 'footer3':
				return $this->config['tmpl_wap_footer3'];
			case 'cards':
				return $this->config['tmpl_wap_cards'];
		}
		return '';
	}

	function loadModules($position)
	{
		echo '<jdoc:include type="modules" name="'.$position.'" style="wml" />';
	}

	function loadModulesAsCards($position)
	{
		echo '<jdoc:include type="modules" name="'.$position.'" style="wmlcards" />';
	}

	function showBreadcrumbs()
	{
		if($this->config['tmpl_wap_pathway'] && (!$this->_ishomepage || $this->config['tmpl_wap_pathwayhome']))
			echo '<jdoc:include type="module" name="breadcrumbs" style="wml" />';
	}

	function showComponent()
	{
		if(!$this->_ishomepage || $this->config['tmpl_wap_componenthome'])
			echo '<jdoc:include type="component" />';
	}

	function showFooter()
	{
		$mainframe =& JFactory::getApplication();
		if($this->config['tmpl_wap_jfooter'])
		{
			/** @var JLanguage $lang */
			$lang =& JFactory::getLanguage();
			$lang->load('com_mobilejoomla', JPATH_ADMINISTRATOR);
			$fyear = (substr(JVERSION,0,3) != '1.5') ? 'Y' : '%Y';
			$version = new JVersion();
?>
<p><small>&copy; <?php echo JHTML::_('date', 'now', $fyear).' '.$mainframe->getCfg('sitename'); ?><br/><?php echo $version->URL; ?><br/><?php echo JText::_('COM_MJ__MOBILE_VERSION_BY');?> <a href="http://www.mobilejoomla.com/">Mobile Joomla!</a></small></p>
<?php
		}
	}

	function processPage($text)
	{
		$doctypes = array (1 => '<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">', '<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.2//EN" "http://www.wapforum.org/DTD/wml_1.2.xml">');
		$pretext = '<?xml version="1.0" encoding="utf-8" ?>'."\n".$doctypes[$this->config['tmpl_wap_doctype']]."\n";

		$text = preg_replace('#<img src="[^"]*arrow(_rtl)?\.png" alt=""\s*/>#', '&gt;', $text); //pathway fix
		$text = preg_replace('#<iframe\s[^>]+? />#is', '', $text);
		$text = preg_replace('#<iframe.+</iframe>#is', '', $text);
		$text = preg_replace('#<object\s[^>]+? />#is', '', $text);
		$text = preg_replace('#<object\s.+?</object>#is', '', $text);
		$text = preg_replace('#<embed\s[^>]+? />#is', '', $text);
		$text = preg_replace('#<embed.+</embed>#is', '', $text);
		$text = preg_replace('#<applet\s[^>]+? />#is', '', $text);
		$text = preg_replace('#<applet\s.+?</applet>#is', '', $text);
		$text = preg_replace('#<script\s[^>]+? />#is', '', $text);
		//$text = preg_replace('#<script\s.+?</script>#is', '', $text);
		$text = preg_replace('#<script([^\'"/>]|"[^"]*?"|\'[^\']*?\')*?>([^\'"/]|"([^"\\\\]|\\\\.)*?"|\'([^\'\\\\]|\\\\.)*?\'|/[^/*]|/\*.*?\*/|//.*?$)*?</script>#ism', '', $text);
		$text = preg_replace('#<h(.*?)>#is', '<big>', $text);
		$text = preg_replace('#</h(.*?)>#is', '</big><br/>', $text);
		$text = preg_replace('#<(ol|ul|dl|div|table)(.*?)>#i', '', $text);
		$text = preg_replace('#</(ol|ul|dl|table)>#i', '', $text);
		$text = preg_replace('#</div>#i', '<br/>', $text);
		$text = preg_replace('#<(td|tr|dd|li|span)(.*?)>#is', '', $text);
		$text = preg_replace('#</(tr|dd|li)>#i', '<br/>', $text);
		$text = str_ireplace('</td>', ' | ', $text);
		$text = str_ireplace('</span>', '', $text);
		$text = str_replace(' | <br/>', '<br/>', $text);
		$text = preg_replace('#<dt(.*?)>#is', '<strong>', $text);
		$text = str_ireplace('</dt>', '</strong><br/>', $text);
		$text = preg_replace('# class="(.*?)"#is', '', $text);
		$text = preg_replace('# rel="(.*?)"#is', '', $text);
		$text = preg_replace('# id="(.*?)"#is', '', $text);
		$text = preg_replace('# style="(.*?)"#is', '', $text);
		$text = preg_replace('# title="(.*?)"#is', '', $text);
		$text = preg_replace('# target="_blank"#is', '', $text);
		$text = trim($text);

		$title = $this->getPageTitle();
		$pos = strpos($text, '<card');
		if($pos===false) // there is no card tag
		{
			$text = '<card id="main" title="'.$title.'">'."\n".$text."\n</card>\n";
		}
		else
		{
			$text = '<card id="main" title="'.$title.'">'."\n".substr($text, 0, $pos)."\n</card>\n".substr($text, $pos);
		}

		$text = "<wml>\n"."<head>\n"."<meta http-equiv=\"Cache-Control\" content=\"max-age=0\" forua=\"true\" />\n"."</head>\n".$text."\n"."</wml>";

		if($this->config['tmpl_wap_img'] == 1)
			$text = preg_replace('#<img [^>]+>#is', '', $text);
		elseif($this->config['tmpl_wap_img'] >= 2)
		{
			$scaletype = $this->config['tmpl_wap_img']-2;
			$text = MobileJoomla::RescaleImages($text, $scaletype);
		}

		$text = str_replace('<br/>', '<br>', $text);
		$text = strip_tags($text, '<a><access><anchor><b><big><br><card><do><em><fieldset><go><head><i><img><input><meta><noop><onevent><optgroup><option><p><postfield><prev><refresh><select><setvar><small><strong><table><td><tr><template><timer><u><wml>');
		$text = str_replace('<br />', '<br/>', $text);
		$text = str_replace('<br>', '<br/>', $text);
		$text = preg_replace('#\s\s+#', ' ', $text);
		$text = preg_replace("#(\n|\r)+#", "\n", $text);

		if($this->config['tmpl_wap_entitydecode'] == 1)
		{
			$text = strtr($text, array ('&lt;' => '&amp;lt;', '&gt;' => '&amp;gt;', '&amp;' => '&amp;amp;'));
			$text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
		}

		return $pretext.$text;
	}
}
