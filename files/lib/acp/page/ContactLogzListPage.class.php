<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wcf\acp\page;

use wcf\data\contact\logz\ContactLogzList;
use wcf\data\contact\option\ContactOptionList;
use wcf\page\SortablePage;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the Contact log list page.
 */
class ContactLogzListPage extends SortablePage
{
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
    public function readParameters()
    {
        parent::readParameters();

        if (!empty($_REQUEST['email'])) {
            $this->email = StringUtil::trim($_REQUEST['email']);
        }
        if (!empty($_REQUEST['receivername'])) {
            $this->receivername = StringUtil::trim($_REQUEST['receivername']);
        }
        if (!empty($_REQUEST['sender'])) {
            $this->sender = StringUtil::trim($_REQUEST['sender']);
        }
        if (isset($_REQUEST['inWork'])) {
            $this->inWork = \intval($_REQUEST['inWork']);
        }
    }

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
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
    public function assignVariables()
    {
        if (!empty($this->objectList)) {
            foreach ($this->objectList as $object) {
                $additionalData = \unserialize($object->additionalData);
                $optionValues = [];
                foreach ($this->options as $option) {
                    $key = 'option' . $option->optionID;
                    if (isset($additionalData[$option->optionID])) {
                        $optionValues[$option->optionID] = $additionalData[$option->optionID];
                    } else {
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
            'sender' => $this->sender,
        ]);
    }
}
