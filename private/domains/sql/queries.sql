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