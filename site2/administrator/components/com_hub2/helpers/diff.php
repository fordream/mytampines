<?php

/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class  Hub2DiffHelper {

    /**
     Diff implemented in pure php, written from scratch.
     Copyright (C) 2003  Daniel Unterberger <diff.phpnet@holomind.de>
     Copyright (C) 2005  Nils Knappmeier next version

     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License
     as published by the Free Software Foundation; either version 2
     of the License, or (at your option) any later version.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with this program; if not, write to the Free Software
     Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

     http://www.gnu.org/licenses/gpl.html

     About:
     I searched a function to compare arrays and the array_diff()
     was not specific enough. It ignores the order of the array-values.
     So I reimplemented the diff-function which is found on unix-systems
     but this you can use directly in your code and adopt for your needs.
     Simply adopt the formatline-function. with the third-parameter of arr_diff()
     you can hide matching lines. Hope someone has use for this.

     Contact: d.u.diff@holomind.de <daniel unterberger>
     **/

    private static function prepareDiff(&$t1,&$t2) {
        # split the source text into arrays of lines
        //$t1 = explode("\n",$old);
        $x=array_pop($t1);
        if ($x>'') {
            $t1[]="$x\n\\ No newline at end of file";
        }
        //$t2 = explode("\n",$new);
        $x=array_pop($t2);
        if ($x>'')  {
            $t2[]="$x\n\\ No newline at end of file";
        }

        # build a reverse-index array using the line as key and line number as value
        # don't store blank lines, so they won't be targets of the shortest distance
        # search
        foreach($t1 as $i=>$x) {
            if ($x>'') {
                $r1[$x][]=$i;
            }
        }
        foreach($t2 as $i=>$x) {
            if ($x>'') {
                $r2[$x][]=$i;
            }
        }
        return array($r1,$r2);
    }
    ## PHPDiff returns the differences between $old and $new, formatted
    ## in the standard diff(1) output format.
    ## t1 is an array of strings
    ## t2 is an array of strings
    ## returns an array with two indexes
    ## value at index 0 is an array of values different in $t1
    ## value at index 1 is an array if values different in $t2
    public static function PHPDiff($t1,$t2) {

        list($r1,$r2) = self::prepareDiff($t1,$t2);
        $a1=0;
        $a2=0;  #  start at beginning of each list
        $actions=array();

        # walk this loop until we reach the end of one of the lists
        while ($a1<count($t1) && $a2<count($t2)) {
            # if we have a common element, save it and go to the next
            if ($t1[$a1]==$t2[$a2]) {
                $actions[]=4;
                $a1++;
                $a2++;
                continue;
            }

            # otherwise, find the shortest move (Manhattan-distance) from the
            # current location
            $best1=count($t1);
            $best2=count($t2);
            $s1=$a1;
            $s2=$a2;
            while(($s1+$s2-$a1-$a2) < ($best1+$best2-$a1-$a2)) {
                $d=-1;
                foreach((array)@$r1[$t2[$s2]] as $n) {
                    if ($n>=$s1) {
                        $d=$n;
                        break;
                    }
                }
                if ($d>=$s1 && ($d+$s2-$a1-$a2)<($best1+$best2-$a1-$a2)) {
                    $best1=$d;
                    $best2=$s2;
                }
                $d=-1;
                foreach((array)@$r2[$t1[$s1]] as $n) {
                    if ($n>=$s2) {
                        $d=$n;
                        break;
                    }
                }
                if ($d>=$s2 && ($s1+$d-$a1-$a2)<($best1+$best2-$a1-$a2)) {
                    $best1=$s1;
                    $best2=$d;
                }
                $s1++;
                $s2++;
            }
            while ($a1<$best1) {
                $actions[]=1;
                $a1++;
            }  # deleted elements
            while ($a2<$best2) {
                $actions[]=2;
                $a2++;
            }  # added elements
        }

        # we've reached the end of one list, now walk to the end of the other
        while($a1<count($t1)) {
            $actions[]=1;
            $a1++;
        }  # deleted elements
        while($a2<count($t2)) {
            $actions[]=2;
            $a2++;
        }  # added elements

        # and this marks our ending point
        $actions[]=8;

        # now, let's follow the path we just took and report the added/deleted
        # elements into $out.
        $op = 0;
        $x0=$x1=0;
        $y0=$y1=0;
        $out1 = array();
        $out2 = array();
        foreach($actions as $act) {
            if ($act==1) {
                $op|=$act;
                $x1++;
                continue;
            }
            if ($act==2) {
                $op|=$act;
                $y1++;
                continue;
            }
            if ($op>0) {
                //$xstr = ($x1==($x0+1)) ? $x1 : ($x0+1).",$x1";
                //$ystr = ($y1==($y0+1)) ? $y1 : ($y0+1).",$y1";
                /*if ($op==1) $out[] = "{$xstr}d{$y1}";
                elseif ($op==3) $out[] = "{$xstr}c{$ystr}";*/
                while ($x0<$x1) {
                    $out1[] =
                    $x0;
                    $x0++;
                }   # deleted elems
                /*if ($op==2) $out[] = "{$x1}a{$ystr}";
                elseif ($op==3) $out[] = '---';*/
                while ($y0<$y1) {
                    $out2[] = $y0;
                    $y0++;
                }   # added elems
            }
            $x1++;
            $x0=$x1;
            $y1++;
            $y0=$y1;
            $op=0;
        }
        //$out1[] = '';
        //$out2[] = '';
        return array($out1, $out2);
    }

    private static function is_tag($text) {
        return preg_match('/<\/?[\w][^>]*>/',$text) > 0;
    }

    private function splitHTML($html) {
        $parts = preg_split('~(</?[\w][^>]*>)~', $html, -1,
        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $final =  array();
        foreach ($parts as $part) {
            if (preg_match('/<\/?[\w][^>]*>/',$part)) {
                $final[] = $part;
            } else {
                $t = explode(' ',$part);
                foreach ($t as $val) {
                    $final[] = $val;
                }
            }
        }
        return $final;
    }
    /*
     * FLEXIcontent is a derivative work of the excellent QuickFAQ component
     * @copyright (C) 2008 Christoph Lukes
     * see www.schlu.net for more information
     *
     * FLEXIcontent is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     */
    /**
     * Return side-by-side displayable difference between old and new content
     * @param $old string the old content
     * @param $new string the new content
     * @param $mode 0 to not show in HTML mode, 1 to show in HTML mode
     * usage list($old,$new) = flexiHtmlDiff($old, $new, $mode)
     * echo $old;
     * echo $new;
     */
    public static function flexiHtmlDiff($old, $new, $mode=0) {
        $t1 = &self::splitHTML($old);
        $t2 = &self::splitHTML($new);
        $out = self::PHPDiff( $t1, $t2 );
        // out array index 0 is deleted items, index 1 is added items
        $html1 = array();
        $html2 = array();
        $currentlyInStrikethrough = false;
        foreach($t1 as $k=>$o) {
            if(in_array($k, $out[0])) {
                // deleted item
                $isTag = self::is_tag($o);
                $s = '';
                if (!$currentlyInStrikethrough && ( ($mode==0 && !$isTag) || $mode ==1))  {
                    $s = "<s style=\"color:red;text-decoration:line-through;\">";
                    $currentlyInStrikethrough = true;
                    // start a tag
                }
                if ($currentlyInStrikethrough && $mode == 0 && $isTag) {
                    $s = "</s>";
                    $currentlyInStrikethrough = false;  // close tag is reach a tag
                }
                $html1[] = $s.($mode?htmlspecialchars($o, ENT_QUOTES):$o);

            } else {
                $s = '';
                if ($currentlyInStrikethrough) {
                    $s = "</s>";
                    $currentlyInStrikethrough = false;
                }
                // matched item
                $html1[] = $s.($mode?htmlspecialchars($o, ENT_QUOTES):$o);
            }
        }
        if ($currentlyInStrikethrough) {
            $html1[] = '</s>';
            $currentlyInStrikethrough = false;
        }
        foreach($t2 as $k=>$n) {
            if(in_array($k, $out[1])) {
                $isTag = self::is_tag($n);
                $s = '';
                if (!$currentlyInStrikethrough && ( ($mode==0 && !$isTag) || $mode ==1))  {
                    $s = "<u style=\"color:green;\">";
                    $currentlyInStrikethrough = true;
                    // start a tag
                }
                if ($currentlyInStrikethrough && $mode == 0 && $isTag) {
                    $s = "</u>";
                    $currentlyInStrikethrough = false;  // close tag is reach a tag
                }
                $html2[] = $s.($mode?htmlspecialchars($n, ENT_QUOTES):$n);

            } else {
                $s = '';
                if ($currentlyInStrikethrough) {
                    $s = "</u>";
                    $currentlyInStrikethrough = false;
                }
                // added item
                $html2[] = $s.($mode?htmlspecialchars($n, ENT_QUOTES):$n);
            }
        }
        if ($currentlyInStrikethrough) {
            $html2[] = '</u>';
            $currentlyInStrikethrough = false;
        }
        $html1 = implode(" ", $html1);
        $html2 = implode(" ", $html2);
        return array($html1, $html2);
    }

    public static function nl2space($string) {
        if(gettype($string)!="string") {
            return false;
        }
        $str = str_replace("\n"," ",$string);
        return $str;
    }

    public static function nl2break($string) {
        if(gettype($string)!="string") {
            return false;
        }
        $str = str_replace("\n","<br />",$string);
        return $str;
    }
    public static function break2nl($string) {
        if(gettype($string)!="string") {
            return false;
        }
        $str = str_replace("<br />","\n",$string);
        return $str;
    }

    public static function sqlLineDiff($old, $new,$showMatchedLines = false) {
        $t1 = explode(",",self::nl2break($old));
        $t2 = explode(",",self::nl2break($new));
        $out = self::PHPDiff( $t1, $t2 );
        // out array index 0 is deleted items, index 1 is added items
        if (!$showMatchedLines) {
            if (count($out[0])==0 && count ($out[1])==0) {
                return array('','');
            }
        }
        $html1 = array();
        $html2 = array();
        foreach($t1 as $k=>$o) {
            if(in_array($k, $out[0])) {
                // deleted item
                $html1[] = "<span style=\"color:red\">".$o."</span>";
            } else {
                // matched item
                $html1[] = $o;
            }
        }
        foreach($t2 as $k=>$n) {
            if(in_array($k, $out[1])) {
                // added item
                $html2[] = "<span style=\"color:red\">".$n."</span>";
            } else {
                // matched item
                $html2[] = $n;
            }
        }
        $html1 = implode(",", $html1);
        $html2 = implode(",", $html2);
        return array($html1, $html2);
    }

    public static function sqlSchemaFileDiff($templateSQLs, $siteSQLs, $breakChar = "\n") {
        $t1 = explode($breakChar,$templateSQLs); // line by line comparison
        $t2 = explode($breakChar,$siteSQLs);
        // index by the table name and then diff each table
        $templateTable = array();
        foreach ($t1 as $templateSQL) {
            $templateSQL = trim($templateSQL);
            if (!empty($templateSQL)) { // skip empty lines
                // get the first part of the table till CREATE TABLE `#__XXXX` (
                $tname = trim(substr($templateSQL,0,strpos($templateSQL,'(')));
                $tname = str_replace('CREATE TABLE ','',$tname);
                $templateTable[$tname] = $templateSQL;
            }
        }
        $siteTable = array();
        foreach ($t2 as $templateSQL) {
            // get the first part of the table till CREATE TABLE `#__XXXX` (
            $templateSQL = trim($templateSQL);
            if (!empty($templateSQL)) { // skip empty lines
                $tname = trim(substr($templateSQL,0,strpos($templateSQL,'(')));
                $tname = str_replace('CREATE TABLE ','',$tname);
                $siteTable[$tname] = $templateSQL;
            }
        }
        $html1 = array();
        $html2 = array();
        foreach ($templateTable as $tname=>$sql) {
            if (array_key_exists($tname,$siteTable)) {
                // do word by word comparison
                // hack to ensure ) VALUES ( does not get trapped as dissimilar
                // when 1st value is different
                list($t1,$t2) = self::sqlLineDiff($sql,$siteTable[$tname]);
                // filter out matched lines
                if ($t1 || $t2) {
                    list($html1[$tname],$html2[$tname]) = array($t1,$t2);
                }
            } else {
                // add to the difference
                $html1[$tname] = '<span style="color:red">'.$sql.'</span>';
            }
        }
        // go through the site table and find which tables do not exist on the template
        foreach ($siteTable as $tname=>$sql) {
            if (!array_key_exists($tname,$templateTable)) {
                $html2[$tname] = '<span style="color:red">'.$sql.'</span>';
            }
        }
        return array($html1, $html2);
    }

    public static function sqlConfigFileDiff($templateSQLs, $siteSQLs, $breakChar = "\n") {
        $t1 = explode($breakChar,$templateSQLs); // line by line comparison
        $t2 = explode($breakChar,$siteSQLs);
        // index by the table name and then diff each table
        $templateTable = array();
        foreach ($t1 as $templateSQL) {
            $templateSQL = trim($templateSQL);
            if (!empty($templateSQL) && preg_match("/^ALTER TABLE/",$templateSQL) == 0
            &&  preg_match("/^TRUNCATE TABLE/",$templateSQL) == 0) { // skip empty lines
                // get the first part of the table till CREATE TABLE `#__XXXX` (
                $tname = trim(substr($templateSQL,0,strpos($templateSQL,'(')));
                $tname = str_replace('INSERT INTO ','',$tname);
                $templateTable[$tname] = preg_replace("/\/\*.*\*\//",'',$templateSQL);
                // removing the unique key from being displayed to user
            }
        }
        $siteTable = array();
        foreach ($t2 as $templateSQL) {
            // get the first part of the table till CREATE TABLE `#__XXXX` (
            $templateSQL = trim($templateSQL);
            if (!empty($templateSQL) && preg_match("/^ALTER TABLE/",$templateSQL) == 0
            &&  preg_match("/^TRUNCATE TABLE/",$templateSQL) == 0) { // skip empty lines
                $tname = trim(substr($templateSQL,0,strpos($templateSQL,'(')));
                $tname = str_replace('INSERT INTO ','',$tname);
                $siteTable[$tname] = preg_replace("/\/\*.*\*\//",'',$templateSQL);
            }
        }
        $html1 = array();
        $html2 = array();
        foreach ($templateTable as $tname=>$sql) {
            if (array_key_exists($tname,$siteTable)) {
                // do word by word comparison
                $sql = str_replace(') VALUES (',',) VALUES (,',$sql);
                $siteSQL = str_replace(') VALUES (',',) VALUES (,',$siteTable[$tname]);
                list($t1,$t2) = self::sqlLineDiff($sql,$siteSQL);
                $t1 = str_replace(',) VALUES (,',') VALUES (',$t1);
                $t2 = str_replace(',) VALUES (,',') VALUES (',$t2);
                // filter out matched lines
                if ($t1 || $t2) {
                    list($html1[$tname],$html2[$tname]) = array($t1,$t2);
                }
            } else {
                // add to the difference
                $html1[$tname] = '<span style="color:red">'.$sql.'</span>';
            }
        }
        // go through the site table and find which tables do not exist on the template
        foreach ($siteTable as $tname=>$sql) {
            if (!array_key_exists($tname,$templateTable)) {
                $html2[$tname] = '<span style="color:red">'.$sql.'</span>';
            }
        }
        return array($html1, $html2);
    }
}
