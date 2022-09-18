<?php 
namespace wcf\data\contact\logz;
use wcf\data\DatabaseObject;
use wcf\util\FileUtil;

/**
 * Represents a contact Log entry
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.contact
 */
class ContactLogz extends DatabaseObject {
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'contact_logz';
	
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableIndexName = 'contactLogzID';
	
	/**
	 * Returns icon name for a file name.
	 */
	public function getIconName($filename) {
		if ($iconName = FileUtil::getIconNameByFilename($filename)) {
			return 'file-' . $iconName . '-o';
		}
		
		return 'paperclip';
	}
}
