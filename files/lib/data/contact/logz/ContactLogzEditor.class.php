<?php
namespace wcf\data\contact\logz;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit contact log entries.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogzEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = ContactLogz::class;
}
