"use strict";

/**
 * Implementation for AJAXProxy-based toggle action for Contact Log.
 *
 * Modified copy of WCF.Action.Toggle in WCF.js:
 * 
 * Class and function collection for WCF.
 * 
 * Major Contributors: Markus Bartz, Tim Duesterhus, Matthias Schmidt and Marcel Werk
 * 
 * @author    Alexander Ebert
 * @copyright    2001-2017 WoltLab GmbH
 * @license    GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
var UZCONTACT = {};

UZCONTACT.UzContact = Class.extend({
    _buttonSelector: '.jsToggleButton',
    _className: '',
    _containerSelector: '',
    _containers: [],

    /**
     * Initializes 'UzContact'-Proxy
     */
    init: function (className, containerSelector, buttonSelector) {
        this._containerSelector = containerSelector;
        this._className = className;
        this._buttonSelector = (buttonSelector) ? buttonSelector : '.jsToggleButton';
        this._containers = [];

        // initialize proxy
        var options = {
            success: $.proxy(this._success, this)
        };
        this.proxy = new WCF.Action.Proxy(options);

        // bind event listener
        this._initElements();
        WCF.DOMNodeInsertedHandler.addCallback('UZCONTACT.UzContact' + this._className.hashCode(), $.proxy(this._initElements, this));
    },

    /**
     * Initializes available element containers.
     */
    _initElements: function () {
        $(this._containerSelector).each($.proxy(function (index, container) {
            var $container = $(container);
            var $containerID = $container.wcfIdentify();

            if (!WCF.inArray($containerID, this._containers)) {
                this._containers.push($containerID);
                $container.find(this._buttonSelector).click($.proxy(this._click, this));
            }
        }, this));
    },

    /**
     * Sends AJAX request.
     */
    _click: function (event) {
        var $target = $(event.currentTarget);
        event.preventDefault();

        WCF.LoadingOverlayHandler.updateIcon($target);
        this._sendRequest($target);
    },

    /**
     * Executes UzContact.
     */
    _execute: function (action, parameters) {
        WCF.LoadingOverlayHandler.updateIcon(parameters.target);
        this._sendRequest(parameters.target);
    },

    _sendRequest: function (object) {
        this.proxy.setOption('data', {
            actionName: 'toggle',
            className: this._className,
            interfaceName: 'wcf\\data\\IToggleAction',
            objectIDs: [$(object).data('objectID')]
        });

        this.proxy.sendRequest();
    },

    /**
     * Toggles status icons.
     */
    _success: function (data, textStatus, jqXHR) {
        this.triggerEffect(data.objectIDs);
    },

    /**
     * Triggers the toggle effect for the objects with the given ids.
     */
    triggerEffect: function (objectIDs) {
        for (var $index in this._containers) {
            var $container = $('#' + this._containers[$index]);
            var $toggleButton = $container.find(this._buttonSelector);
            if (WCF.inArray($toggleButton.data('objectID'), objectIDs)) {
                $container.wcfHighlight();
                this._toggleButton($container, $toggleButton);
            }
        }
    },

    /**
     * Triggers the toggle effect on a button
     */
    _toggleButton: function ($container, $toggleButton) {
        var $newTitle = '';

        // toggle icon source
        WCF.LoadingOverlayHandler.updateIcon($toggleButton, false);
        if ($toggleButton.hasClass('fa-square-o')) {
            $toggleButton.removeClass('fa-square-o').addClass('fa-check-square-o');
            $newTitle = ($toggleButton.data('disableTitle') ? $toggleButton.data('disableTitle') : WCF.Language.get('wcf.acp.contact.logz.notInWork'));
            $toggleButton.attr('title', $newTitle);
        }
        else {
            $toggleButton.removeClass('fa-check-square-o').addClass('fa-square-o');
            $newTitle = ($toggleButton.data('enableTitle') ? $toggleButton.data('enableTitle') : WCF.Language.get('wcf.acp.contact.logz.inWork'));
            $toggleButton.attr('title', $newTitle);
        }

        // toggle css class
        $container.toggleClass('disabled');
    }
});
