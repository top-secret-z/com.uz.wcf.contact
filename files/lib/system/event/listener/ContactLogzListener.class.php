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
namespace wcf\system\event\listener;

use wcf\data\contact\logz\ContactLogzAction;
use wcf\data\contact\recipient\ContactRecipient;
use wcf\system\WCF;
use wcf\util\UserUtil;

/**
 * Listen to contact form sending
 */
class ContactLogzListener implements IParameterizedEventListener
{
    /**
     * @see    wcf\system\event\IEventListener::execute()
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        // check modules
        if (!MODULE_CONTACT_FORM) {
            return;
        }

        // only send
        if ($eventObj->getActionName() != 'send') {
            return;
        }

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
            $hasAttachments = \count($attachmentList);
            if ($hasAttachments) {
                foreach ($attachmentList as $attachment) {
                    $attachments[] = [
                        'attachmentID' => $attachment->attachmentID,
                        'filename' => $attachment->filename,
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
            'attachments' => $hasAttachments ? \serialize($attachments) : null,
            'additionalData' => \serialize($additionalData),
        ];

        $logzAction = new ContactLogzAction([], 'create', ['data' => $data]);
        $logzAction->executeAction();
    }
}
