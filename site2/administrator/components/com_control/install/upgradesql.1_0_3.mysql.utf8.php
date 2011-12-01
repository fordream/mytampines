-- <?php /** $Id: upgradesql.1_0_3.mysql.utf8.php 1055 2008-10-31 00:01:58Z eddieajau $ */ defined('_JEXEC') or die() ?>;

UPDATE `#__components`
 SET `name`='Control',
  admin_menu_img='components/com_control/media/images/icon-16-jx.png'
 WHERE `option`='com_control';
