<?php
/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Class with helper functions for Daabase dumps
 */
class Hub2AdminDatabaseDump {
    static $db = null;

    private static function dumpTable($tableName,$orderClause='',$uniqueCombination=array(),
    $bulkinsert = false) {
        self::$db->setQuery("SELECT * from ".$tableName.' '.$orderClause);
        $rows = self::$db->loadAssocList();
        $sql = array();
        $valueRows = array();
        $fields = array();
        $values = array();
        foreach ($rows as $row) {
            $fields = array();
            $values = array();
            foreach ($row as $key=>$val) {
                $fields[] = '`'.$key.'`';
                if (is_int($val)) {
                    $values[] = $val;
                } else if (is_null($val)) {
                    $values[] = 'NULL';
                } else if ($tableName == '#__hub2_workflow' &&
                ($key == 'is_start' || $key == 'is_end')) {
                    $values[] = '0x0'.$val;
                } else {
                    $values[] = self::$db->Quote($val);
                }
                if (in_array($key,$uniqueCombination)) {
                    $uniq[] = $val;
                }
            }
            $pre = '';
            if (count($uniqueCombination) > 0) {
                $uniq = array();
                foreach($uniqueCombination as $key) {
                    $uniq[] = $row[$key];
                }
                $pre = '/*'.implode("_",$uniq).'*/';
            }
            if ($bulkinsert) {
                $valueRows[] = '('.implode(",",$values).')';
            } else {
                $sql[] = "INSERT INTO ".$pre.$tableName. " (\n".implode(",\n",$fields).
                "\n) VALUES (\n".
                implode(",\n",$values)."\n);";
            }
        }
        if ($bulkinsert) {
            $sql[] = "INSERT INTO ".$pre.$tableName. " (".implode(",",$fields).") VALUES \n".
            implode(" ,\n",$valueRows).";";
        }
        $result = "TRUNCATE TABLE {$tableName};\nALTER TABLE {$tableName} AUTO_INCREMENT=1;\n";
        $result .= implode("\n",$sql);
        $result .= "\n";
        return $result;
    }

    /**
     * Returns an array with the config (as a string), and md5 as a string(optional)
     */
    public function dumpConfig($generateMD5=false, $useDB = null) {
        if ($useDB) {
            self::$db = $useDB;
        } else {
            self::$db = JFactory::getDBO();
        }
        $result = array();
        $result['components'] = self::dumpTable("#__components","ORDER BY id",
        array('name','admin_menu_link','option'),$generateMD5 == false);
        $result['plugins'] = self::dumpTable("#__plugins","ORDER BY id",
        array('name','element','folder'),$generateMD5 == false);
        $result['modules'] = self::dumpTable("#__modules","ORDER BY id",
        array('title','position','module'),$generateMD5 == false);
        $result['modules_menu'] = self::dumpTable("#__modules_menu","ORDER BY moduleid,menuid",
        array('moduleid','menuid'),$generateMD5 == false);
        $result['menu'] = self::dumpTable("#__menu","ORDER BY id",
        array('menutype','name','link'),$generateMD5 == false);
        $result['menu_types'] = self::dumpTable("#__menu_types","ORDER BY id",
        array('menutype'),$generateMD5 == false);
        $result['hub2_content_fields'] = self::dumpTable("#__hub2_content_fields","ORDER BY id",
        array('field_type','name'),$generateMD5 == false);
        $result['template'] = self::dumpTable("#__templates_menu","ORDER BY template",
        array('template'),$generateMD5 == false);
        $result['hub2_content_fields_type_relations'] =
        self::dumpTable("#__hub2_content_fields_type_relations","ORDER BY field_id, type_id",
        array('field_id','type_id'),$generateMD5 == false);
        $result['hub2_content_types'] = self::dumpTable("#__hub2_content_types","ORDER BY id",
        array('name'),$generateMD5 == false);
        $result['hub2_workflow'] = self::dumpTable("#__hub2_workflow","ORDER BY id",
        array('id'),$generateMD5 == false);
        $result['hub2_static_data'] = self::dumpTable("#__hub2_static_data","ORDER BY id",
        array('code'),$generateMD5 == false);
        $response['config'] = implode("\n",$result);
        if ($generateMD5) {
            $md5 = array();
            foreach ($result as $name=>$str) {
                $md5[] = $name.'-'.md5($str);
            }
            $md5 = implode("\n",$md5);
            $response['md5'] = $md5;
        }
        return $response;
    }

    /**
     * Returns an array with the schema, and md5(optional) and table count
     */
    public static function dumpSchema($ignoreEngine=false,$generateMD5=false, &$useDB = null) {
        // get the Tables
        if ($useDB) {
            $db = $useDB;
        } else {
            $db = &JFactory::getDBO();
        }
        $db->setQuery('SHOW TABLES');
        $tableNames = $db->loadResultArray();
        $result = array();
        $str = '';
        $count = count($tableNames);
        $md5 = array();
        foreach ($tableNames as $tableName) {
            $db->setQuery("SHOW CREATE TABLE ".$tableName);
            $obj = $db->loadAssoc('Table');
            $t = $obj['Create Table'];
            if ($ignoreEngine) {
                $t = preg_replace('/TYPE=.*/i','',$t);
                $t = preg_replace('/ENGINE=.*/i','',$t);
            } else {
                $t = preg_replace('/TYPE=/i','ENGINE=',$t);
                $t = preg_replace('/AUTO_INCREMENT=.*/i','',$t);
            }
            $t = preg_replace('/`jos_/i','`#__',$t);
            $str .= trim($t).";\n";
            if ($generateMD5) {
                $md5[] = $tableName.'-'.md5($t);
            }
        }
        $md5 = implode("\n",$md5);
        $result = array('schema'=> $str, 'md5'=>$md5, 'count'=> $count);
        return $result;
    }
}