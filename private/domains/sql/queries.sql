-- mysql -u root -p taskmgr < taskmgr_mod.sql

-- SELEZIONA UTENTE E NOME E AUTHORITA DEL GRUPPO
SELECT u.*,g.Nome,r.Authority FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p WHERE p.id = 1 AND p.id = pg.Project AND pg.tGroup = gr.Groupid AND r.id = gr.Roleid AND gr.Userid = u.id;

-- CANCELLA GRUPPO
DELETE FROM ProjectGroup WHERE tGroup=1 AND Project=2;

-- SELEZIONA UTENTi E NOME GRUPPi DEL PROGETTO
SELECT u.*,g.Nome as GroupName FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p, tGroup as g WHERE p.id =1 AND p.id = pg.Project AND pg.tGroup = gr.Groupid AND r.id = gr.Roleid AND g.id = pg.tGroup AND gr.Userid = u.id;

-- SELEZIONA I GRUPPI DEL PROGETTO
SELECT g.* FROM tGroup as g, Project as p, ProjectGroup as pg WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=1;

-- SELEZIONA I PROGETTI DELL UTENTE
SELECT DISTINCT p.* FROM User as u,Project as p,ProjectGroup as pg, GroupRole as gr WHERE p.id = pg.Project AND gr.Groupid = pg.tGroup AND u.id = gr.Userid AND u.id=3;

-- SELEZIONA I GRUPPI DELL UTENTE
SELECT g.* FROM User as u, tGroup as g, GroupRole as gr WHERE gr.Userid = u.id AND gr.Groupid = g.id AND u.id = 3;

-- SELEZIONA GLI UTENTI E IL NUMERO DEI PROGETTI A CUI PARTECIPANO
SELECT u.*,(SELECT COUNT( DISTINCT p.id) FROM User as u1,Project as p,ProjectGroup as pg, GroupRole as gr WHERE p.id = pg.Project AND gr.Groupid = pg.tGroup AND u.id = gr.Userid AND u1.id = u.id) as projects FROM User as u;

-- SELEZIONA LE TASK DI UN UTENTE 
-- (QUELLE CHE SONO STATE CONDIVISE CON LUI HANNO LA COLONNA 'CONDIVISA' PARI ALL'ID
-- DELLA TABELLA SharedTask)
SELECT t.*, IFNULL(st.id,0) as Condivisa FROM User as u, Assignee as a,Grouprole as gr, tGroup as g, Task as t LEFT JOIN SharedTask as st ON t.id = st.Task WHERE u.id = 1 AND ((a.User = u.id AND t.Assignee = a.id) OR (t.Assignee = a.id AND u.id = gr.Userid AND a.tGroup = g.id AND gr.Groupid = g.id) OR (st.User = u.id AND t.id = st.Task)) GROUP BY t.id;

-- SELEZIONA GLI UTENTI DI UN PROGETTO CON RUOLO E NOME DEL GRUPPO
SELECT u.*,r.Authority,g.Nome as GroupName 
FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p, tGroup as g 
WHERE p.id = 1
AND p.id = pg.Project 
AND pg.tGroup = gr.Groupid 
AND r.id = gr.Roleid 
AND g.id = pg.tGroup
AND gr.Userid = u.id GROUP BY u.id;

-- SELEZIONA LE TASK DI UN PROGETTO
SELECT t.*, IFNULL(st.id,0) as Condivisa FROM Project as p, Task as t LEFT JOIN SharedTask as st ON st.Task = t.id WHERE p.id = 1 AND p.id = t.Project;

-- SELEZIONA UNA TASK E L'UTENTE CON CUI E CONDIVISA
SELECT t.*, IFNULL(st.id,null) as Condivisore FROM Task as t LEFT JOIN SharedTask as st ON t.id = st.Task LEFT JOIN User as u ON u.id = st.User WHERE t.id=3;