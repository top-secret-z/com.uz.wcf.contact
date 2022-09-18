DROP TABLE IF EXISTS wcf1_contact_logz;
CREATE TABLE wcf1_contact_logz (
    contactLogzID        INT(10) AUTO_INCREMENT PRIMARY KEY,
    time                INT(10) NOT NULL DEFAULT 0,
    receiverID            INT(10),
    receiverEmail        VARCHAR(255) NOT NULL,
    receivername        VARCHAR(255) NOT NULL,
    inWork                TINYINT(1) NOT NULL DEFAULT 0,
    userID                INT(10),
    username            VARCHAR(255) NOT NULL,
    sender                VARCHAR(255) NOT NULL,
    email                VARCHAR(255) NOT NULL,
    ipAddress            VARCHAR(39) NOT NULL DEFAULT '',
    attachments            TEXT,
    hasAttachments        TINYINT(1) NOT NULL DEFAULT 0,
    additionalData        MEDIUMTEXT

    KEY (receiverID),
    KEY (inWork),
    KEY (time),
    KEY (userID),
    KEY (ipAddress),
    KEY (hasAttachments)
);

ALTER TABLE wcf1_contact_logz ADD FOREIGN KEY (receiverID) REFERENCES wcf1_contact_recipient (recipientID) ON DELETE SET NULL;
ALTER TABLE wcf1_contact_logz ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
