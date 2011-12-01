<?php

/**
 * @version     $Id: helper.php 16385 2010-04-23 10:44:15Z ian $
 * @package     Joomla
 * @subpackage  Installation
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JInstallationHelper {

    /**
     * Creates a new database
     * @param object Database connector
     * @param string Database name
     * @param boolean utf-8 support
     * @param string Selected collation
     * @return boolean success
     */
    function createDatabase(& $db, $DBname, $DButfSupport) {
        if ($DButfSupport) {
            $sql = "CREATE DATABASE `$DBname` CHARACTER SET `utf8`";
        } else {
            $sql = "CREATE DATABASE `$DBname`";
        }

        $db->setQuery($sql);
        $db->query();
        $result = $db->getErrorNum();

        if ($result != 0) {
            return false;
        }

        return true;
    }

    /**
     * Sets character set of the database to utf-8 with selected collation
     * Used in instances of pre-existing database
     * @param object Database object
     * @param string Database name
     * @param string Selected collation
     * @return boolean success
     */
    function setDBCharset(& $db, $DBname) {
        if ($db->hasUTF()) {
            $sql = "ALTER DATABASE `$DBname` CHARACTER SET `utf8`";
            $db->setQuery($sql);
            $db->query();
            $result = $db->getErrorNum();
            if ($result != 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Backs up existing tables
     * @param object Database connector
     * @param array An array of errors encountered
     */
    function backupDatabase(& $db, $DBname, $DBPrefix, & $errors) {
        // Initialize backup prefix variable
        // TODO: Should this be user-defined?
        $BUPrefix = 'bak_';

        $query = "SHOW TABLES FROM `$DBname`";
        $db->setQuery($query);
        $errors = array ();
        if ($tables = $db->loadResultArray()) {
            foreach ($tables as $table) {
                if (strpos($table, $DBPrefix) === 0) {
                    $butable = str_replace($DBPrefix, $BUPrefix, $table);
                    $query = "DROP TABLE IF EXISTS `$butable`";
                    $db->setQuery($query);
                    $db->query();
                    if ($db->getErrorNum()) {
                        $errors[$db->getQuery()] = $db->getErrorMsg();
                    }
                    $query = "RENAME TABLE `$table` TO `$butable`";
                    $db->setQuery($query);
                    $db->query();
                    if ($db->getErrorNum()) {
                        $errors[$db->getQuery()] = $db->getErrorMsg();
                    }
                }
            }
        }

        return count($errors);
    }


    /**
     *
     */
    function populateDatabase(& $db, $sqlfile, & $errors) {
        if( !($buffer = file_get_contents($sqlfile)) ) {
            return -1;
        }

        $queries = JInstallationHelper::splitSql($buffer);
        $i = 0;
        foreach ($queries as $query) {
            $query = trim($query);
            if ($query != '' && $query {0} != '#')  {
                //$db->setQuery($query);
                //echo $query .'<br />';
                $db->Execute($query);
                if ($i > 0 ||
                $sqlfile !== JPATH_SITE.DS.'database'.DS.'joomla-1.5.20-base+external.sql') {
                    JInstallationHelper::getDBErrors($errors, $db );
                }
                $i++;
            }
        }
        return count($errors);
    }

    /**
     * @param string
     * @return array
     */
    function splitSql($sql) {
        $sql = trim($sql);
        $sql = preg_replace("/\n\#[^\n]*/", '', "\n".$sql);
        $buffer = array ();
        $ret = array ();
        $in_string = false;

        for ($i = 0; $i < strlen($sql) - 1; $i ++) {
            if ($sql[$i] == ";" && !$in_string) {
                $ret[] = substr($sql, 0, $i);
                $sql = substr($sql, $i +1);
                $i = 0;
            }

            if ($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
                $in_string = false;
            } elseif (!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") &&
            (!isset ($buffer[0]) || $buffer[0] != "\\")) {
                $in_string = $sql[$i];
            }
            if (isset ($buffer[1])) {
                $buffer[0] = $buffer[1];
            }
            $buffer[1] = $sql[$i];
        }

        if (!empty ($sql)) {
            $ret[] = $sql;
        }
        return ($ret);
    }


    function & getDBO($driver, $host, $user, $password, $database, $prefix, $select = true) {
        jimport('joomla.database.database');
        $options    = array ( 'driver' => $driver, 'host' => $host,
            'user' => $user, 'password' => $password, 'database' => $database,
            'prefix' => $prefix, 'select' => $select );
        $db = & JDatabase::getInstance( $options );

        return $db;
    }

    function getDBErrors( & $errors, $db ) {
        if ($db->getErrorNum() > 0) {
            $errors[] = $db->getErrorMsg();
        }
    }
}
?>
