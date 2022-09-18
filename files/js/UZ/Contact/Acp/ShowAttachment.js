/**
 * Provides the dialog to show contact attachments.
 * 
 * @author        2018-2022 Zaydowicz
 * @license        GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package        com.uz.wcf.contact
 */
define(['Ajax', 'Language', 'Ui/Dialog'], function(Ajax, Language, UiDialog) {
    "use strict";

    function UzContactAcpShowAttachment() { this.init(); }

    UzContactAcpShowAttachment.prototype = {
        init: function() {
            var buttons = elBySelAll('.jsShowAttachmentButton');
            for (var i = 0, length = buttons.length; i < length; i++) {
                buttons[i].addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
            }
        },

        _ajaxSetup: function() {
            return {
                data: {
                    actionName:    'showAttachment',
                    className:    'wcf\\data\\contact\\logz\\ContactLogzAction'
                }
            };
        },

        _ajaxSuccess: function(data) {
            UiDialog.open(this, data.returnValues.template);
        },

        _dialogSetup: function() {
            return {
                id: 'showAttachment',
                options: {
                    title: Language.get('wcf.acp.contact.logz.attachments')
                },
                source: null
            };
        },

        _click: function(event) {
            var objectID = elData(event.currentTarget, 'object-id');

            Ajax.api(this, {
                parameters: {
                    objectID: objectID
                }
            });
        }
    };
    return UzContactAcpShowAttachment;
});
