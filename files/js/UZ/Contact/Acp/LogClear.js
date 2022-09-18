/**
 * Clears the contact log.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
define(['Ajax', 'Language', 'Ui/Confirmation'], function(Ajax, Language, UiConfirmation) {
	"use strict";
	
	function UzContactAcpLogClear() { this.init(); }
	
	UzContactAcpLogClear.prototype = {
		init: function() {
			var buttons = elBySelAll('.jsContactLogClear');
			for (var i = 0, length = buttons.length; i < length; i++) {
				buttons[i].addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
			}
		},
		
		_click: function(event) {
			UiConfirmation.show({
				confirm: function() {
					Ajax.apiOnce({
						data: {
							actionName: 'clearAll',
							className: 'wcf\\data\\contact\\logz\\ContactLogzAction'
						},
						success: function() {
							window.location.reload();
						}
					});
				},
				message: Language.get('wcf.acp.contact.logz.clear.confirm')
			});	
		}
	};
	return UzContactAcpLogClear;
});
