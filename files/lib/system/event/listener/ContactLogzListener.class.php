<?php
namespace wcf\system\event\listener;
use wcf\data\contact\recipient\ContactRecipient;
use wcf\data\contact\logz\ContactLogzAction;
use wcf\system\event\IEventListener;
use wcf\system\WCF;
use wcf\util\UserUtil;

/**
 * Listen to contact form sending
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogzListener implements IParameterizedEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		// check modules
		if (!MODULE_CONTACT_FORM) return;
		
		// only send
		if ($eventObj->getActionName() != 'send') return;
		
		// collect data
		$user = WCF::getUser();
		$params = $eventObj->getParameters();
		$optionHandler = $params['optionHandler'];
		$receiverID = $params['recipientID'];
		$receiver = new ContactRecipient($receiverID);
		$receiverEmail = WCF::getLanguage()->get($receiver->email);
		$receivername = WCF::getLanguage()->get($receiver->name);
		
		$additionalData = [];
		foreach ($optionHandler->getOptions() as $option) {
			$object = $option['object'];
			$additionalData[$object->optionID] = $object->getFormattedOptionValue(true);
		}
		
		$attachments = [];
		$hasAttachments = 0;
		if (isset($params['attachmentHandler'])) {
			$attachmentList = $params['attachmentHandler']->getAttachmentList();
			$attachments = [];
			$hasAttachments = count($attachmentList);
			if ($hasAttachments) {
				foreach($attachmentList as $attachment) {
					$attachments[] = [
							'attachmentID' => $attachment->attachmentID,
							'filename' => $attachment->filename
					];
				}
			}
		}
		
		$ipAddress = '';
		if (LOG_IP_ADDRESS) {
			$ipAddress = UserUtil::convertIPv6To4(WCF::getSession()->ipAddress);
		}
		
		$data = [
				'time' => TIME_NOW,
				'email' => $params['email'],
				'sender' => $params['name'],
				'receiverID' => $receiverID,
				'receiverEmail' => $receiverEmail,
				'receivername' => $receivername,
				'userID' => $user->userID ? $user->userID : null,
				'username' => $user->username ? $user->username : 'wcf.user.guest',
				'ipAddress' => $ipAddress,
				'hasAttachments' => $hasAttachments < 128 ? $hasAttachments : 128,
				'attachments' => $hasAttachments ? serialize($attachments) : null,
				'additionalData' => serialize($additionalData)
		];
		
		$logzAction = new ContactLogzAction([], 'create', ['data' => $data]);
		$logzAction->executeAction();
	}
}
