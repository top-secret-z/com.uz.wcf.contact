<?php
namespace wcf\system\event\listener;
use wcf\data\contact\recipient\ContactRecipient;
use wcf\system\email\mime\MimePartFacade;
use wcf\system\email\mime\RecipientAwareTextMimePart;
use wcf\system\email\Email;
use wcf\system\email\Mailbox;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Listen to contact form for sending copy to sender
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactFormSendCopyListener implements IParameterizedEventListener {
	/**
	 * instance of ContactForm
	 */
	protected $eventObj;
	
	/**
	 * send copy
	 */
	protected $sendCopy = 0;
	
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$this->eventObj = $eventObj;
		$this->$eventName();
	}
	
	/**
	 * Handles the assignVariables event.
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign([
				'sendCopy' => $this->sendCopy
		]);
	}
	
	/**
	 * Handles the readFormParameters event.
	 */
	protected function readFormParameters() {
		if (CONTACT_FORM_COPY == 'on') {
			$this->sendCopy = 1;
		}
		else if (CONTACT_FORM_COPY == 'off'){
			$this->sendCopy = 0;
		}
		else {
			if (isset($_POST['sendCopy'])) $this->sendCopy = intval($_POST['sendCopy']);
		}
	}
	
	/**
	 * Handles the save event.
	 */
	protected function save() {
		$this->eventObj->additionalFields = array_merge($this->eventObj->additionalFields, [
				'sendCopy' => $this->sendCopy
		]);
		
		if ($this->sendCopy) {
			$defaultLanguage = LanguageFactory::getInstance()->getDefaultLanguage();
			$recipient = new ContactRecipient($this->eventObj->recipientID);
			$messageData = [];
			
			if (!ENABLE_ENTERPRISE_MODE) {
				if (CONTACT_FORM_COPY_FULL) {
					$optionHandler = $this->eventObj->optionHandler;
					$options = [];
					foreach ($optionHandler->getOptions() as $option) {
						$object = $option['object'];
						if ($object->optionType === 'date' && !$object->getOptionValue()) {
							continue;
						}
						
						$options[] = [
								'isMessage' => $object->isMessage(),
								'title' => $object->getLocalizedName($defaultLanguage),
								'value' => $object->getFormattedOptionValue(true)
						];
					}
					
					// build message data
					$messageData = [
							'options' => $options,
							'recipient' => $recipient,
							'name' => $this->eventObj->name,
							'emailAddress' => $this->eventObj->email
					];
				}
			}
			
			// build mail
			$email = new Email();
			$email->addRecipient(new Mailbox($this->eventObj->email));
			$email->setSubject($defaultLanguage->get('wcf.contact.mail.copy.subject'));
			
			if (CONTACT_FORM_COPY_FULL && !ENABLE_ENTERPRISE_MODE) {
				$email->setBody(new MimePartFacade([
						new RecipientAwareTextMimePart('text/html', 'email_contact_copy_full', 'wcf', $messageData),
						new RecipientAwareTextMimePart('text/plain', 'email_contact_copy_full', 'wcf', $messageData)
				]));
			}
			else {
				$email->setBody(new MimePartFacade([
						new RecipientAwareTextMimePart('text/html', 'email_contact_copy', 'wcf', $messageData),
						new RecipientAwareTextMimePart('text/plain', 'email_contact_copy', 'wcf', $messageData)
				]));
			}
			
			// add reply-to tag
			$email->setReplyTo(new Mailbox($this->eventObj->email));
			
			// send mail
			$email->send();
		}
	}
}
