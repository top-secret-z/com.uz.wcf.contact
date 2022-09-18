<?php
namespace wcf\data\contact\logz;
use wcf\data\DatabaseObjectList;
use wcf\system\WCF;

/**
 * Represents a list of contact log entries.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogzList extends DatabaseObjectList {
	/**
	 * @see	\wcf\data\DatabaseObjectList::$className
	 */
	public $className = ContactLogz::class;
}
