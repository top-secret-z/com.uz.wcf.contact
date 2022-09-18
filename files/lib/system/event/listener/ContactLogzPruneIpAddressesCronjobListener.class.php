<?php
namespace wcf\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;

/**
 * Prunes the stored ip addresses.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogzPruneIpAddressesCronjobListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$eventObj->columns['wcf'.WCF_N.'_contact_logz']['ipAddress'] = 'time';
	}
}
