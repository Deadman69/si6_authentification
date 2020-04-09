-- LA BASE DE DONNEE DOIT S'APPELLER "secured"

ALTER TABLE members DROP CONSTRAINT FK_MEMBERS_REFERENCE_TOKENS;
ALTER TABLE members DROP CONSTRAINT FK_MEMBERS_REFERENCE_ACCESS;
ALTER TABLE access DROP CONSTRAINT FK_ACCESS_REFERENCE_MEMBERS;
ALTER TABLE tokens DROP CONSTRAINT FK_TOKENS_REFERENCE_MEMBERS;
ALTER TABLE failedLogin DROP CONSTRAINT FK_FAILEDLOGIN_REFERENCE_ERRORSCODE;
ALTER TABLE failedRegister DROP CONSTRAINT FK_FAILEDREGISTER_REFERENCE_ERRORSCODE;
ALTER TABLE pageForbidden DROP CONSTRAINT FK_PAGEFORBIDDEN_REFERENCE_ERRORSCODE;
ALTER TABLE successLogin DROP CONSTRAINT FK_SUCCESSLOGIN_REFERENCE_MEMBERS;
ALTER TABLE logs DROP CONSTRAINT FK_LOGS_REFERENCE_LOGSCODE;
ALTER TABLE logs DROP CONSTRAINT FK_LOGS_REFERENCE_MEMBERS;
ALTER TABLE generalChat DROP CONSTRAINT FK_GENERALCHAT_REFERENCE_MEMBERS;
ALTER TABLE tokens DROP CONSTRAINT FK_TOKENS_REFERENCE_ACCESS;
ALTER TABLE messagesPrives DROP CONSTRAINT FK_MESSAGESPRIVES_REFERENCE_CONVERSATIONS;
ALTER TABLE messagesPrives DROP CONSTRAINT FK_MESSAGESPRIVES_REFERENCE_MEMBERS;
ALTER TABLE conversationsAccess DROP CONSTRAINT FK_CONVERSATIONSACCESS_REFERENCE_MEMBERS;
ALTER TABLE conversationsAccess DROP CONSTRAINT FK_CONVERSATIONSACCESS_REFERENCE_CONVERSATION;
ALTER TABLE conversations DROP CONSTRAINT FK_CONVERSATIONS_REFERENCE_MEMBERS;
ALTER TABLE pageForbidden DROP CONSTRAINT FK_PAGE_FORBIDDEN_REFERENCE_MEMBERS;


DROP TABLE members;
DROP TABLE failedLogin;
DROP TABLE failedRegister;
DROP TABLE pageForbidden;
DROP TABLE tokens;
DROP TABLE access;
DROP TABLE errorsCode;
DROP TABLE successLogin;
DROP TABLE logs;
DROP TABLE logsCode;
DROP TABLE generalChat;
DROP TABLE conversations;
DROP TABLE messagesPrives;
DROP TABLE conversationsAccess;



-- [[ Create Table ]] --

CREATE TABLE members
(
    member_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    member_login VARCHAR(30) NOT NULL,
    member_pseudo VARCHAR(30) NOT NULL,
    member_password VARCHAR(255) NOT NULL,
    member_registrationIpAddr VARCHAR(50) NOT NULL,
    member_registrationDate DATETIME NOT NULL,
    member_registrationMac VARCHAR(70) NOT NULL,
    member_registrationToken INT, -- FK to tokens_id
    member_lastIpAddr VARCHAR(50) NOT NULL,
    member_lastDateConnection DATETIME NOT NULL,
    member_lastMac VARCHAR(70) NOT NULL,
    member_access INT NOT NULL DEFAULT 2 -- FK to access_id (2 = user)
);

CREATE TABLE failedLogin
(
	failed_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	failed_login VARCHAR(30) NOT NULL,
	failed_ip VARCHAR(50) NOT NULL,
	failed_date DATETIME NOT NULL,
	failed_mac VARCHAR(70) NOT NULL,
	failed_error VARCHAR(15) NOT NULL -- FK to errorsCode_codeValue
);

CREATE TABLE failedRegister
(
	failed_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	failed_login VARCHAR(30) NOT NULL,
	failed_ip VARCHAR(50) NOT NULL,
	failed_date DATETIME NOT NULL,
	failed_mac VARCHAR(70) NOT NULL,
	failed_tokenValue VARCHAR(255) NOT NULL,
	failed_error VARCHAR(15) NOT NULL -- FK to errorsCode_codeValue
);

CREATE TABLE pageForbidden
(
	failed_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	failed_login INT NOT NULL, -- FK to members.member_id (if user is not logged, we set to '1' = anonymous)
	failed_request VARCHAR(255) NOT NULL,
	failed_ip VARCHAR(50) NOT NULL,
	failed_date DATETIME NOT NULL,
	failed_mac VARCHAR(70) NOT NULL,
	failed_error VARCHAR(15) NOT NULL -- FK to errorsCode_codeValue
);

CREATE TABLE tokens
(
	token_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	token_value VARCHAR(255) NOT NULL,
	token_createdBy INT NOT NULL, -- FK to members_id
	token_createDate DATETIME NOT NULL,
	token_expireDate DATETIME NOT NULL,
	token_usedDate DATETIME,
	token_promoteTo INT NOT NULL DEFAULT 2, -- FK to access.access_id (user)
	token_used BOOLEAN NOT NULL DEFAULT 0
);


CREATE TABLE access
(
	access_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	access_power INT NOT NULL,
	access_name VARCHAR(255) NOT NULL,
	access_createdBy INT -- FK to member_id
);


CREATE TABLE errorsCode
(
	code_value VARCHAR(15) PRIMARY KEY NOT NULL,
	code_explain TEXT NOT NULL,
	code_solve TEXT
);

CREATE TABLE successLogin
(
	success_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	success_login INT NOT NULL, -- FK to members.member_id
	success_ip VARCHAR(50) NOT NULL,
	success_date DATETIME NOT NULL,
	success_mac VARCHAR(70) NOT NULL
);

CREATE TABLE logs
(
	log_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	log_login INT NOT NULL, -- FK to members.member_id
	log_date DATETIME NOT NULL,
	log_code VARCHAR(15) NOT NULL -- FK to logsCode.code_value
);

CREATE TABLE logsCode
(
	code_value VARCHAR(15) PRIMARY KEY NOT NULL,
	code_explain TEXT NOT NULL,
	code_solve TEXT
);

CREATE TABLE generalChat
(
	message_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	message_sender INT NOT NULL, -- FK to members.member_id
	message_date DATETIME NOT NULL,
	message_text TEXT NOT NULL,
	message_visible BOOLEAN NOT NULL DEFAULT 1
);


CREATE TABLE conversationsAccess
(
	access_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	access_owner INT NOT NULL, -- FK to members.member_id
	access_conversation INT NOT NULL -- FK to conversations.conversation_id
);

CREATE TABLE conversations
(
	conversation_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	conversation_dateCreation DATETIME NOT NULL,
	conversation_initiator INT NOT NULL, -- FK to members.member_id
	conversation_openned BOOLEAN NOT NULL DEFAULT 1,
	conversation_visible BOOLEAN NOT NULL DEFAULT 1,
	conversation_subject VARCHAR(255) NOT NULL
);

CREATE TABLE messagesPrives
(
	message_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	message_conversationID INT NOT NULL, -- FK to conversations.conversation_id
	message_sender INT NOT NULL, -- FK to members.member_id
	message_date DATETIME NOT NULL,
	message_text TEXT NOT NULL
);



-- [[ Alter Table ]] --

ALTER TABLE conversations
ADD CONSTRAINT FK_CONVERSATIONS_REFERENCE_MEMBERS
FOREIGN KEY (conversation_initiator) REFERENCES members(member_id);

ALTER TABLE pageForbidden
ADD CONSTRAINT FK_PAGE_FORBIDDEN_REFERENCE_MEMBERS
FOREIGN KEY (failed_login) REFERENCES members(member_id);

ALTER TABLE conversationsAccess
ADD CONSTRAINT FK_CONVERSATIONSACCESS_REFERENCE_MEMBERS
FOREIGN KEY (access_owner) REFERENCES members(member_id);

ALTER TABLE conversationsAccess
ADD CONSTRAINT FK_CONVERSATIONSACCESS_REFERENCE_CONVERSATION
FOREIGN KEY (access_conversation) REFERENCES conversations(conversation_id);

ALTER TABLE messagesPrives
ADD CONSTRAINT FK_MESSAGESPRIVES_REFERENCE_CONVERSATIONS
FOREIGN KEY (message_conversationID) REFERENCES conversations(conversation_id);

ALTER TABLE messagesPrives
ADD CONSTRAINT FK_MESSAGESPRIVES_REFERENCE_MEMBERS
FOREIGN KEY (message_sender) REFERENCES members(member_id);

ALTER TABLE generalChat
ADD CONSTRAINT FK_GENERALCHAT_REFERENCE_MEMBERS
FOREIGN KEY (message_sender) REFERENCES members(member_id);

ALTER TABLE logs
ADD CONSTRAINT FK_LOGS_REFERENCE_LOGSCODE
FOREIGN KEY (log_code) REFERENCES logsCode(code_value);

ALTER TABLE logs
ADD CONSTRAINT FK_LOGS_REFERENCE_MEMBERS
FOREIGN KEY (log_login) REFERENCES members(member_id);

ALTER TABLE members
ADD CONSTRAINT FK_MEMBERS_REFERENCE_TOKENS
FOREIGN KEY (member_registrationToken) REFERENCES tokens(token_id);

ALTER TABLE members
ADD CONSTRAINT FK_MEMBERS_REFERENCE_ACCESS
FOREIGN KEY (member_access) REFERENCES access(access_id);

ALTER TABLE access
ADD CONSTRAINT FK_ACCESS_REFERENCE_MEMBERS
FOREIGN KEY (access_createdBy) REFERENCES members(member_id);

ALTER TABLE tokens
ADD CONSTRAINT FK_TOKENS_REFERENCE_MEMBERS
FOREIGN KEY (token_createdBy) REFERENCES members(member_id);

ALTER TABLE failedLogin
ADD CONSTRAINT FK_FAILEDLOGIN_REFERENCE_ERRORSCODE
FOREIGN KEY (failed_error) REFERENCES errorsCode(code_value);

ALTER TABLE failedRegister
ADD CONSTRAINT FK_FAILEDREGISTER_REFERENCE_ERRORSCODE
FOREIGN KEY (failed_error) REFERENCES errorsCode(code_value);

ALTER TABLE pageForbidden
ADD CONSTRAINT FK_PAGEFORBIDDEN_REFERENCE_ERRORSCODE
FOREIGN KEY (failed_error) REFERENCES errorsCode(code_value);

ALTER TABLE successLogin
ADD CONSTRAINT FK_SUCCESSLOGIN_REFERENCE_MEMBERS
FOREIGN KEY (success_login) REFERENCES members(member_id);

ALTER TABLE tokens
ADD CONSTRAINT FK_TOKENS_REFERENCE_ACCESS
FOREIGN KEY (token_promoteTo) REFERENCES access(access_id);




-- [[ INSERT INTO ]] --


INSERT INTO access(access_power, access_name, access_createdBy) VALUES(0, "banned", NULL);
INSERT INTO access(access_power, access_name, access_createdBy) VALUES(1, "user", NULL);
INSERT INTO access(access_power, access_name, access_createdBy) VALUES(50, "moderator", NULL);
INSERT INTO access(access_power, access_name, access_createdBy) VALUES(100, "admin", NULL);


INSERT INTO members(member_login, member_pseudo, member_password, member_registrationIpAddr, member_registrationDate, member_registrationMac, member_lastIpAddr, member_lastDateConnection, member_lastMac, member_access) VALUES("anonymous", "anonymous", "{utt!k7BD6µ9?T*9W92FN9v<g", "localhost", "2000-01-01", "localmac", "localhost", "2000-01-01", "localmac", 1 );
INSERT INTO members(member_login, member_pseudo, member_password, member_registrationIpAddr, member_registrationDate, member_registrationMac, member_lastIpAddr, member_lastDateConnection, member_lastMac, member_access) VALUES("mod", "mod", "$2y$10$8o/2yyJu.XurHRw8.5KJsuBSFlOVf8BCkt6kXzGWdWteCOZbH7ucq", "localhost", "2000-01-01", "localmac", "localhost", "2000-01-01", "localmac", 2 );
INSERT INTO members(member_login, member_pseudo, member_password, member_registrationIpAddr, member_registrationDate, member_registrationMac, member_lastIpAddr, member_lastDateConnection, member_lastMac, member_access) VALUES("admin", "admin", "$2y$10$NvmKKTX4hOgxohGhzIqfHeSeexPNBm8yibR5REjVbKFwl0WaU.Mme", "localhost", "2000-01-01", "localmac", "localhost", "2000-01-01", "localmac", 3 );

INSERT INTO tokens(token_value, token_createdBy, token_createDate, token_expireDate, token_used, token_promoteTo) VALUES("default", 1, "2000-01-01", "2000-01-01", 1, 1);

INSERT INTO errorsCode(code_value, code_explain) VALUES("00x0000", "Missing error code");

INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0001", "Username already used for register", "Changing username in the appropriate field");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0002", "More than 1 user finded for this username", "Check the database");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0003", "Token doesn't exist", "Create a token or use an existing token");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0004", "Token already used", "Create a token or use an available token");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0005", "Passowrd mismatch", "Create a token or use an available token");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0006", "At least one field is empty", "Fill all the fields");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("00x0007", "You have forbidden caracters in your name", "Remove theses caracters");

INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("01x0001", "Password mismatch", "Use a valid Password");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("01x0002", "Username don't find", "Use a valid Username");

INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("02x0001", "User not allowed, admin required", "Give him admin rights");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("02x0002", "User not allowed, mod required", "Give him mod rights");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("02x0003", "User not logged, connection required", "Connect to the website");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("02x0004", "User is banned", "Unban him");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("02x0005", "User not allowed, user required", "User try to access a conversation where he don't have access");

INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("03x0001", "Old password don't correspond", "Use the actual password");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("03x0002", "Password verification failed", "Use the same password in the verif");
INSERT INTO errorsCode(code_value, code_explain, code_solve) VALUES("03x0003", "At least one field is empty", "Fill all the fields");



INSERT INTO logsCode(code_value, code_explain, code_solve) VALUES("0000x00", "Error", "");
INSERT INTO logsCode(code_value, code_explain, code_solve) VALUES("0001x01", "Creating a token", "The user had create a token");




INSERT INTO failedLogin(failed_login, failed_ip, failed_date, failed_mac, failed_error) VALUES("default", "localhost", "2000-01-01", "localmac", "00x0000");

INSERT INTO failedRegister(failed_login, failed_ip, failed_date, failed_mac, failed_tokenValue, failed_error) VALUES("default", "localhost", "2000-01-01", "localmac", "default","00x0000");

INSERT INTO pageForbidden(failed_login, failed_request, failed_ip, failed_date, failed_mac, failed_error) VALUES(1, "default", "localhost", "2000-01-01", "localmac", "00x0000");







-- Update pour respecter la foreign key

UPDATE access SET access_createdBy = 1; -- UPDATE All

UPDATE members SET member_registrationToken = 1; -- UPDATE For all users (anonymous & admin & mod)