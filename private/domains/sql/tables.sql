CREATE TABLE IF NOT EXISTS `User` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Nome` VARCHAR(32) NOT NULL,
    `Cognome` VARCHAR(32) NOT NULL,
    `DataNascita` DATE NOT NULL,
    `NomeUtente` VARCHAR(32) NOT NULL,
    Password CHAR(60) NOT NULL
);

CREATE TABLE IF NOT EXISTS `Role`(
	`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Authority` VARCHAR(32) NOT NULL
);

CREATE TABLE IF NOT EXISTS `UserRole`(
	`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Userid` int NOT NULL,
    `Roleid` int NOT NULL,
    FOREIGN KEY (`Userid`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Roleid`) REFERENCES `Role`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Project`(
	`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`Nome` VARCHAR(32) NOT NULL,
    `Descrizione` TEXT NOT NULL,
    `Completato` BOOL NOT NULL,
    `DataInizio` DATE NOT NULL,
    `DataCompletamento` DATE NULL,
    `DataScadenza` DATE NOT NULL,
    `Creatore` int,
	FOREIGN KEY (Creatore) REFERENCES `User`(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `tGroup`(
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Nome` VARCHAR(32) NOT NULL
);

CREATE TABLE IF NOT EXISTS `GroupRole`(
    `id` int NOT NULL AUTO_INCREMENT,
    `Userid` int NOT NULL,
    `Groupid` int NOT NULL,
    `Roleid` int NOT NULL,
    FOREIGN KEY (`Userid`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Groupid`) REFERENCES `tGroup`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Roleid`) REFERENCES Role(`id`) ON DELETE CASCADE,
	PRIMARY KEY (`Userid`, `Groupid`),
    UNIQUE KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `ProjectGroup`(
    `id` int NOT NULL AUTO_INCREMENT,
    `tGroup` int NOT NULL,
    `Project` int NOT NULL,
    FOREIGN KEY (`tGroup`) REFERENCES `tGroup`(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`Project`) REFERENCES `Project`(`id`) ON DELETE CASCADE,
    PRIMARY KEY (`tGroup`, `Project`),
    UNIQUE KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `Assignee`(
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `User` int,
    `tGroup` int,
    FOREIGN KEY (`User`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tGroup`) REFERENCES `tGroup`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Task`(
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Nome` VARCHAR(32) NOT NULL,
    `Descrizione` TEXT NOT NULL,
    `DataCreazione` DATE NOT NULL,
    `DataScadenza` DATE NOT NULL,
    `DataCompletamento` DATE NULL,
    `Completata` BOOL NOT NULL,
    `User` int NOT NULL,
    `Project` int NOT NULL,
    `Assignee` int DEFAULT NULL,
    FOREIGN KEY (`User`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Project`) REFERENCES `Project`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Assignee`) REFERENCES `Assignee`(`id`) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS `TaskList`(
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Task` int NOT NULL,
    `Completata` BOOL NOT NULL,
	FOREIGN KEY (`Task`) REFERENCES `Task`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `SharedTask`(
    `id` int NOT NULL AUTO_INCREMENT,
    `User` int NOT NULL,
    `Task` int NOT NULL,
	PRIMARY KEY (`User`, `Task`),
    UNIQUE KEY (`id`),
    FOREIGN KEY (`User`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Task`) REFERENCES `Task`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `tList`(
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Task` int NOT NULL,
    `TaskList` int NOT NULL,
    PRIMARY KEY (`Task`, `TaskList`),
    UNIQUE KEY (`id`),
    FOREIGN KEY (`Task`) REFERENCES `Task`(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`TaskList`) REFERENCES `TaskList`(`id`) ON DELETE CASCADE
);