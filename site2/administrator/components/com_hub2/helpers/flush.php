<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Class with helper functions for flush of PHP/Joomla Output buffer
 * useful for scripts running for a long time on IIS
 */
class Hub2FlushHelper {
    static $_level;
    static $_flushStarted = false;

    public function startFlush($echoDiv = true) {
        // flush so we see response instantly
        if (!headers_sent() && !self::$_flushStarted) {
            ini_set('output_buffering',0);
            ini_set('implicit_flush',1);
            self::$_level = ob_get_level();
            for ($i=0 ; $i < self::$_level; $i++) {
                ob_end_flush();
            }
            if ($echoDiv) {
                echo "<div style=\"display:none\">";
                ob_flush();
                flush();
            }
            self::$_flushStarted = true;
        }
    }

    public function endFlush($echoDiv = true) {
        if (self::$_flushStarted) {
            if ($echoDiv) {
                echo '</div>';
            }
            for ($i=0 ; $i < self::$_level; $i++) {
                ob_start();
            }
        }
    }

    public function sendMessage($msg) {
        echo $msg;
        if (self::$_flushStarted) {
            ob_flush();
            flush();
        }
    }
}
