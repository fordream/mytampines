<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
	
JToolBarHelper::title( JText::_( 'AUP_ABOUT' ), 'systeminfo' );
// TODO : JToolBarHelper::custom -> Why not work ?
//JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::back();
JToolBarHelper::help( 'screen.alphauserpoints', true );		
?>
<table class="noshow">
	<tr>
		<td width="100%" valign="top"><img src="components/com_alphauserpoints/assets/images/aup_logo.png" alt="" /><br />
		<p>AlphaUserPoints is the first component for Joomla 1.5.x (native mode) created to  provide a method for users to accumulate points for
performing certain actions on your website such as posting articles,
invite new users, invite a friend to read an article, etc... It also provides an API that allows
developers to easily add other actions.<br />
  AlphaUserPoints is useful in providing an incentive for users to participate in the website, and be more active.<br />
  <br />
  ALPHAUSERPOINTS IS DISTRIBUTED &quot;AS IS&quot;. NO WARRANTY OF ANY KIND IS EXPRESSED OR IMPLIED. YOU USE IT AT YOUR OWN RISK.<br />
  THE AUTHOR WILL NOT BE LIABLE FOR ANY DAMAGES, INCLUDING BUT NOT LIMITED TO DATA LOSS, LOSS OF PROFITS OR ANY OTHER KIND OF LOSS WHILE USING OR MISUSING THIS SCRIPT.  <br />
  <br />
  <b>Author</b> : Bernard Gilly<br />
  <br />
  <b>Official website</b> : <a href="http://www.alphaplug.com" target="_blank">www.alphaplug.com</a>        
		<p><b>Contact :</b> <a href="mailto:contact@alphaplug.com">contact@alphaplug.com</a> <br>
          <br>
          <b>Credits</b>: 
          <span class="smallgrey">Special thank's for testing, good suggestions, developments, &amp; translations to</span> Sami Haaranen (<a href="http://www.joomlaportal.fi/" target="_blank">www.joomlaportal.fi</a>), Mike Gusev (<a href="http://www.migusbox.com" target="_blank">www.migusbox.com</a>), Martin Sebastian Brilla Ghia (<a class="fixed" href="http://www.mgscreativa.com" target="_blank">www.mgscreativa.com</a>), PaoloGabriele Babbini (<a href="http://www.pgb75.it" target="_blank">www.pgb75.it</a>), Adrien Roussel, Dorian Gilly.<br />
          <br />
		    <b><?php echo JText::_( 'AUP_VERSION' ) . " " . _ALPHAUSERPOINTS_NUM_VERSION ; ?></b><br />
		    <br />
		    AlphaUserPoints is Free Software released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU/GPL License</a>.
		    <b><br />
	      Ever thought about giving something back?</b><br>
	Please make a donation if you find AlphaUserPoints useful and want to support its continued development.
	Your donations help by  hardware, hosting services and other expenses that come up as we develop, protect and promote AlphaUserPoints and other free components.
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="image" src="<?php echo JURI::base(true).'/components/com_alphauserpoints/assets/images/Paypal_DonateButton.gif'; ?>" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border:0"/>
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
" />
</form>
        </p>
	  </td>
	</tr>
</table>