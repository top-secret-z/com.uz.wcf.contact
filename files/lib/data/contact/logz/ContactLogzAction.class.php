<?php
namespace wcf\data\contact\logz;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\attachment\Attachment;
use wcf\data\attachment\AdministrativeAttachment;
use wcf\data\IToggleAction;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Executes contact log related actions.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogzAction extends AbstractDatabaseObjectAction implements IToggleAction{
	/**
	 * @inheritDoc
	 */
	protected $className = ContactLogzEditor::class;
	
	/**
	 * @inheritDoc
	 */
	protected $permissionsDelete = ['admin.contact.canManageContactForm'];
	protected $permissionsUpdate = ['admin.contact.canManageContactForm'];
	
	/**
	 * @inheritDoc
	 */
	protected $requireACP = ['delete', 'toggle', 'update', 'clearAll', 'showAttachment'];
	
	// logEntry
	public $contactLogz = null;
	
	/**
	 * @inheritDoc
	 */
	public function validateClearAll() {
		if (!WCF::getSession()->getPermission('admin.contact.canManageContactForm')) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function clearAll() {
		$sql = "DELETE FROM	wcf".WCF_N."_contact_logz";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
	}
	
	/**
	 * @inheritDoc
	 */
	public function validateDelete() {
		if (empty($this->objects)) {
			$this->readObjects();
			
			if (empty($this->objects)) {
				throw new UserInputException('objectIDs');
			}
		}
		
		if (!WCF::getSession()->getPermission('admin.contact.canManageContactForm')) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function validateToggle() {
		parent::validateUpdate();
	}
	
	/**
	 * @inheritDoc
	 */
	public function toggle() {
		foreach ($this->objects as $contact) {
			$contact->update([
					'inWork' => $contact->inWork ? 0 : 1
			]);
		}
	}
	
	/**
	 * Validates the showAttachment action.
	 */
	public function validateShowAttachment() {
		if (!WCF::getSession()->getPermission('admin.contact.canManageContactForm')) {
			throw new PermissionDeniedException();
		}
		
		$this->contactLogz = new ContactLogz($this->parameters['objectID']);
		if (!$this->contactLogz->contactLogzID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * Executes the showAttachment action.
	 */
	public function showAttachment() {
		// get attachments
		$temps = unserialize($this->contactLogz->attachments);
		
		$attachments = [];
		foreach ($temps as $attachment) {
			$test = new Attachment($attachment['attachmentID']);
			if (!$test->attachmentID) {
				$attachments[] = [
						'object' => null,
						'filename' => $attachment['filename']
				];
			}
			else {
				$test = new AdministrativeAttachment($test);
				$test->username = $this->contactLogz->sender;
				$attachments[] = [
						'object' => $test,
						'filename' => ''
				];
				$url = LinkHandler::getInstance()->getLink('Attachment', [
						'object' => $test
				]);
			}
		}
		
		WCF::getTPL()->assign([
				'contact' => $this->contactLogz,
				'attachments' => $attachments
		]);
		
		return [
				'template' => WCF::getTPL()->fetch('contactLogzAttachments')
		];
	}
}
