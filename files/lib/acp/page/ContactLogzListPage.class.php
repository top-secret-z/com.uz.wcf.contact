<?php
namespace wcf\acp\page;
use wcf\data\contact\option\ContactOptionList;
use wcf\data\contact\logz\ContactLogzList;
use wcf\page\SortablePage;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the Contact log list page.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogzListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.contactLogz.list';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.contact.canManageContactForm'];
	
	/**
	 * @inheritDoc
	 */
	public $neededModules = ['MODULE_CONTACT_FORM'];
	
	/**
	 * @inheritDoc
	 */
	public $itemsPerPage = 10;
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'contactLogzID';
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['contactLogzID', 'inWork', 'time', 'receivername', 'username', 'sender', 'email', 'ipAddress', 'hasAttachments'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = ContactLogzList::class;
	
	/**
	 * option data
	 */
	public $options = [];
	
	/**
	 * filter
	 */
	public $email = '';
	public $inWork = -1;
	public $receivername = '';
	public $sender = '';
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (!empty($_REQUEST['email'])) $this->email = StringUtil::trim($_REQUEST['email']);
		if (!empty($_REQUEST['receivername'])) $this->receivername = StringUtil::trim($_REQUEST['receivername']);
		if (!empty($_REQUEST['sender'])) $this->sender = StringUtil::trim($_REQUEST['sender']);
		if (isset($_REQUEST['inWork'])) $this->inWork = intval($_REQUEST['inWork']);
	}
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		// option data
		$optionList = new ContactOptionList();
		$optionList->getConditionBuilder()->add('isDisabled = ?', [0]);
		$optionList->sqlOrderBy = 'optionID ASC';
		$optionList->readObjects();
		$this->options = $optionList->getObjects();
		
		if (empty($this->options)) {
			$this->objectList->getConditionBuilder()->add('contactLogzID = ?', [0]);
		}
		
		// filter
		if (!empty($this->email)) {
			$this->objectList->getConditionBuilder()->add('email LIKE ?', ['%' . $this->email . '%']);
		}
		if (!empty($this->receivername)) {
			$this->objectList->getConditionBuilder()->add('receivername LIKE ?', ['%' . $this->receivername . '%']);
		}
		if (!empty($this->sender)) {
			$this->objectList->getConditionBuilder()->add('sender LIKE ?', ['%' . $this->sender . '%']);
		}
		
		if ($this->inWork != -1) {
			$this->objectList->getConditionBuilder()->add('inWork = ?', [$this->inWork]);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		if (!empty($this->objectList)) {
			foreach ($this->objectList as $object) {
				$additionalData = unserialize($object->additionalData);
				$optionValues = [];
				foreach ($this->options as $option) {
					$key = 'option' . $option->optionID;
					if (isset($additionalData[$option->optionID])) {
						$optionValues[$option->optionID] = $additionalData[$option->optionID];
					}
					else {
						$optionValues[$option->optionID] = '';
					}
				}
				$object->option = $optionValues;
			}
		}
		
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'options' => $this->options,
				'email' => $this->email,
				'inWork' => $this->inWork,
				'receivername' => $this->receivername,
				'sender' => $this->sender
		]);
	}
}
