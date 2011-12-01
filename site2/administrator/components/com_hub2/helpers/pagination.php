<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
jimport('joomla.html.pagination');
/**
 * Pagination Class.  Provides a common interface for content pagination for the
 * Joomla! Framework
 *
 * @package     Joomla.Framework
 * @subpackage  HTML
 * @since       1.5
 */
class Hub2Pagination extends JPagination {

    /**
     * Creates a dropdown box for selecting how many records to show per page
     *
     * @access  public
     * @return  string  The html for the limit # input box
     * @since   1.0
     */
    function getLimitBox() {
        $jApp = &JFactory::getApplication();

        // Initialize variables
        $limits = array ();

        // Make the option list
        for ($i = 5; $i <= 30; $i += 5) {
            $limits[] = JHTML::_('select.option', "$i");
        }
        $limits[] = JHTML::_('select.option', '50');
        $limits[] = JHTML::_('select.option', '100');

        $selected = $this->_viewall ? 0 : $this->limit;

        // Build the select list
        if ($jApp->isAdmin()) {
            $html = JHTML::_('select.genericlist',  $limits, 'limit',
                'class="inputbox" size="1" onchange="submitform();"',
                'value', 'text', $selected);
        } else {
            $html = JHTML::_('select.genericlist',  $limits, 'limit',
                'class="inputbox" size="1"
                onchange="this.form.limitstart.value=0; this.form.submit()"',
                'value', 'text', $selected);
        }
        return $html;
    }

    /**
     * Create and return the pagination data object
     *
     * @access  public
     * @return  object  Pagination data object
     * @since   1.5
     */
    function _buildDataObject() {
        $jApp = &JFactory::getApplication();
        $data = parent::_buildDataObject();
        $view = JRequest::getVar('view');
        $option = JRequest::getVar('option');
        $s = "javascript:void(0);\" ";
        $s .= "onclick=\"document.searchForm.limitstart.value=%s; document.searchForm.submit();";
        if (!$jApp->isAdmin() && $view =='sitesearch' && $option == 'com_hub2') {
            $base = $data->all->base?$data->all->base:0;
            $data->all->link = sprintf($s,$base);

            $base = $data->start->base?$data->start->base:0;
            $data->start->link  = sprintf($s,$base);
            $base = $data->previous->base?$data->previous->base:0;
            $data->previous->link = sprintf($s,$base);

            $base = $data->next->base?$data->next->base:0;
            $data->next->link   = sprintf($s,$base);
            $base = $data->end->base?$data->end->base:0;
            $data->end->link    = sprintf($s,$base);

            for ($i = 0; $i < count($data->pages); $i ++) {
                $base = $data->pages[$i]->base?$data->pages[$i]->base:0;
                $data->pages[$i]->link  = sprintf($s,$base);
            }
        }
        return $data;
    }
}