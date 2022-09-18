<?php
namespace wcf\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;

/**
 * Exports user data iaw Gdpr.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class UzContactGdprExportListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		// only IP addresses
		$eventObj->data['com.uz.wcf.contact'] = [
				'ipAddresses' => $eventObj->exportIpAddresses('wcf'.WCF_N.'_contact_logz', 'ipAddress', 'time', 'userID')
		];
	}
}
