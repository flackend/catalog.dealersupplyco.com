<?php
	/**
	 * This view contains the page footer that will be displayed for the normal catalog
	 * area of the site that the sales reps have access to.
	 * This view will close out any tags opened in the header view and add
	 * any additional content to the bottom of the site if neccessary.
	 * 
	 * @package CI_DSSalesRepApp
	 * @subpackage Views-Catalog
	 * @category Views-Catalog
	 * 
	 * @author Nicholas Marshall <njmarshall@ethixsystems.com>
	 * @since 2009-03-17
	 * @version 2009-03-17
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 */
?>
		<br />
		</td>
	</tr>
</table>
<?php if(constant(APP_IDENT.'_ENABLE_GOOGLE_ANALYTICS') && constant(APP_IDENT.'_GOOGLE_ANALYTICS_KEY') != ""): ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("<?=constant(APP_IDENT.'_GOOGLE_ANALYTICS_KEY')?>");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php endif; ?>
</body>
</html>