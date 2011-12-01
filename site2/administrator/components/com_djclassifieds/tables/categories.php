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
defined('_JEXEC') or die('Restricted access');



class TableCategories extends JTable

{

	var $id = null;

	var $name = null;

	var $parent_id = null;

	var $price = null;

	var $published = 1; //Amol => null to 1

	var $description = null;

	var $icon_url = null;

	var $ordering = 0;

	var $autopublish = 0;

	function __construct(&$db)
	{

		parent::__construct( '#__djcf_categories', 'id', $db);

	}

	/** Hyperlocalizer **/
	   /**
     * Description
     *
     * @access public
     * @param $dirn
     * @param $where
     */
    function getIDsThatChangeOnMove( $dirn, $where='' )
    {
        if (!in_array( 'ordering',  array_keys($this->getProperties())))
        {
            $this->setError( get_class( $this ).' does not support ordering' );
            return false;
        }

        $k = $this->_tbl_key;

        $sql = "SELECT $this->_tbl_key, ordering FROM $this->_tbl";

        if ($dirn < 0)
        {
            $sql .= ' WHERE ordering < '.(int) $this->ordering;
            $sql .= ($where ? ' AND '.$where : '');
            $sql .= ' ORDER BY ordering DESC';
        }
        else if ($dirn > 0)
        {
            $sql .= ' WHERE ordering > '.(int) $this->ordering;
            $sql .= ($where ? ' AND '. $where : '');
            $sql .= ' ORDER BY ordering';
        }
        else
        {
            $sql .= ' WHERE ordering = '.(int) $this->ordering;
            $sql .= ($where ? ' AND '.$where : '');
            $sql .= ' ORDER BY ordering';
        }

        $this->_db->setQuery( $sql, 0, 1 );

        $items = array();
        $row = null;
        $row = $this->_db->loadObject();
        if (isset($row))
        {
            $items[] = $this->$k;
            $items[] = $row->$k;
            /*
            $query = 'UPDATE '. $this->_tbl
            . ' SET ordering = '. (int) $row->ordering
            . ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->$k)
            ;
            $this->_db->setQuery( $query );

            if (!$this->_db->query())
            {
                $err = $this->_db->getErrorMsg();
                JError::raiseError( 500, $err );
            }
            $query = 'UPDATE '.$this->_tbl
            . ' SET ordering = '.(int) $this->ordering
            . ' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($row->$k)
            ;
            $this->_db->setQuery( $query );

            if (!$this->_db->query())
            {
                $err = $this->_db->getErrorMsg();
                JError::raiseError( 500, $err );
            }
            $this->ordering = $row->ordering;
            */
        }
        else
        {
            $items[] = $this->$k;
            /*
            $query = 'UPDATE '. $this->_tbl
            . ' SET ordering = '.(int) $this->ordering
            . ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->$k)
            ;
            $this->_db->setQuery( $query );

            if (!$this->_db->query())
            {
                $err = $this->_db->getErrorMsg();
                JError::raiseError( 500, $err );
            }
            */
        }
        return $items;
    }

}

?>
