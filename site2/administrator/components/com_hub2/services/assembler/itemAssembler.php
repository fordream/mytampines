<?php
/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class Hub2AssemblerItem {

    public static function getReplaceSQLForPropagationMultiple($items) {
        if (count($items) == 0) {
            return false;
        }
        $fieldSql = self::getReplaceSQLFieldForPropagation($items[0]);
        $fieldSql = $fieldSql . ' VALUES ';
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $valueSql = self::getReplaceSQLValueForPropagation($item);
            if ($i == 0) {
                $fieldSql = $fieldSql . $valueSql;
            } else {
                $fieldSql = $fieldSql . ',' . $valueSql;
            }
        }
        return $fieldSql;
    }

    public static function getReplaceSQLForPropagation($item) {
        $fieldSql = self::getReplaceSQLFieldForPropagation($item);
        $valueSql = self::getReplaceSQLValueForPropagation($item);
        return $fieldSql . ' VALUES ' .$valueSql;
    }

    private static function getReplaceSQLFieldForPropagation($item) {
        $fmtsql = ' (%s) ';
        $db =& JFactory::getDBO();
        $vars =  get_object_vars($item);

        foreach ($vars as $k => $v) {
            if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                continue;
            }
            $field[]=$db->nameQuote( $k );
        }
        return sprintf( $fmtsql, implode( ",", $field ));
    }

    private static function getReplaceSQLValueForPropagation($item) {
        $fmtsql = ' (%s) ';
        $db =& JFactory::getDBO();
        $tmp = array();
        $vars = get_object_vars($item);// get_object_vars($this);
        foreach ($vars as $k => $v) {
            if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                continue;
            }
            if ($v === null) {
                $val = 'NULL';
            } else {
                $val = $db->isQuoted( $k ) ? $db->Quote( $v ) : (int)$v;
            }
            $field[]=$db->nameQuote( $k );
            $values[] = $val;
        }
        return sprintf( $fmtsql, implode( ",", $values ));
    }

    public static function getUpdateSQL($item) {
        $db =& JFactory::getDBO();
        $vars = get_object_vars($item);// get_object_vars($this);
        $values = array();
        foreach ($vars as $k => $v) {
            if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                continue;
            }
            if ($v === null) {
                $val = 'NULL';
            } else {
                $val = $db->isQuoted( $k ) ? $db->Quote( $v ) : (int)$v;
            }
            $values[] = $db->nameQuote( $k )."=".$val;
        }
        return implode( ",", $values );
    }

}