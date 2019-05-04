CREATE TABLE IF NOT EXISTS User (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(32) NOT NULL,
    Cognome VARCHAR(32) NOT NULL,
    DataNascita DATE NOT NULL,
    NomeUtente VARCHAR(32) NOT NULL,
    Password CHAR(60) NOT NULL
);

CREATE TABLE IF NOT EXISTS Role(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Authority VARCHAR(32) NOT NULL
);

CREATE TABLE IF NOT EXISTS UserRole(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Userid int NOT NULL,
    Roleid int NOT NULL,
    FOREIGN KEY (Userid) REFERENCES User(id),
    FOREIGN KEY (Roleid) REFERENCES Role(id)
);

CREATE TABLE IF NOT EXISTS Project(
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Nome VARCHAR(32) NOT NULL,
    Descrizione TEXT NOT NULL,
    Completato BOOL NOT NULL,
    DataInizio DATE NOT NULL,
    DataCompletamento DATE NULL,
    DataScadenza DATE NOT NULL,
    Creatore int NOT NULL,
	FOREIGN KEY (Creatore) REFERENCES User(id)
);

CREATE TABLE IF NOT EXISTS tGroup(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(32) NOT NULL
);

CREATE TABLE IF NOT EXISTS GroupRole(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Userid int NOT NULL,
    Groupid int NOT NULL,
    Roleid int NOT NULL,
    FOREIGN KEY (Userid) REFERENCES User(id),
    FOREIGN KEY (Groupid) REFERENCES tGroup(id),
    FOREIGN KEY (Roleid) REFERENCES Role(id)
);

CREATE TABLE IF NOT EXISTS ProjectGroup(
    id int NOT NULL AUTO_INCREMENT,
    tGroup int NOT NULL,
    Project int NOT NULL,
    FOREIGN KEY (tGroup) REFERENCES tGroup(id),
	FOREIGN KEY (Project) REFERENCES Project(id),
    PRIMARY KEY (tGroup, Project),
    UNIQUE KEY (id)
);


CREATE TABLE IF NOT EXISTS Assignee(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    User int,
    tGroup int,
    FOREIGN KEY (User) REFERENCES User(id),
    FOREIGN KEY (tGroup) REFERENCES tGroup(id)
);

CREATE TABLE IF NOT EXISTS TaskList(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Completata BOOL NOT NULL
);

CREATE TABLE IF NOT EXISTS Task(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(32) NOT NULL,
    Descrizione TEXT NOT NULL,
    DataCreazione DATE NOT NULL,
    DataScadenza DATE NOT NULL,
    DataCompletamento DATE NULL,
    Completata BOOL NOT NULL,
    User int NOT NULL,
    Project int NOT NULL,
    Assignee int NOT NULL,
    TaskList int,
    FOREIGN KEY (User) REFERENCES User(id),
    FOREIGN KEY (Project) REFERENCES Project(id),
    FOREIGN KEY (Assignee) REFERENCES Assignee(id),
    FOREIGN KEY (TaskList) REFERENCES TaskList(id)
);

CREATE TABLE IF NOT EXISTS SharedTask(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    User int NOT NULL,
    Task int NOT NULL,
    FOREIGN KEY (User) REFERENCES User(id),
    FOREIGN KEY (Task) REFERENCES Task(id)
);

CREATE TABLE IF NOT EXISTS tList(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Task int NOT NULL,
    TaskList int NOT NULL,
    FOREIGN KEY (Task) REFERENCES Task(id),
	FOREIGN KEY (TaskList) REFERENCES TaskList(id)
);