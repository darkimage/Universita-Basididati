-- mysqldump -u root -p taskmgr > taskmgr_mod.sql // backup
-- mysql -u root -p taskmgr < taskmgr_mod.sql     // restore

------------------------------------- PROJECTS -----------------------------------------

-- SELEZIONA GLI UTENTI DI UN PROGETTO NOME DEL GRUPPO E RUOLO NEL GRUPPO /
SELECT u.*,r.Authority,g.Nome as GroupName 
FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p, tGroup as g 
WHERE p.id = 1
AND p.id = pg.Project 
AND pg.tGroup = gr.Groupid 
AND r.id = gr.Roleid 
AND g.id = pg.tGroup
AND gr.Userid = u.id GROUP BY u.id;


-- SELEZIONA UNA TASK E L'UTENTE CON CUI E CONDIVISA SE LA TASK NON HA CONDIVISIONE LA COLONNA CONDIVISORE E MESSA A NULL /
SELECT t.*, IF(st.id=null,null,u.id) as Condivisore, IFNULL(st.id,null) as SharedTask FROM Task as t LEFT JOIN SharedTask as st ON t.id = st.Task LEFT JOIN User as u ON u.id = st.User WHERE t.id=:id

-- SELEZIONA I GRUPPI DEL PROGETTO /
SELECT g.* FROM tGroup as g, Project as p, ProjectGroup as pg WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id= :projid;

------------------------------------- GROUPS -----------------------------------------

-- SELEZIONA OGNI GRUPPO E PER OGNI GRUPPO IL NUMERO DI UTENTI E IL NUMERO DI PROGETTI
SELECT g.*,(
        SELECT COUNT(gr.id) FROM 
        tGroup as g2 LEFT JOIN GroupRole as gr ON g2.id = gr.Groupid 
        WHERE g2.id = g.id GROUP BY g2.id
    ) as users,
    (
        SELECT COUNT(p.id) FROM 
        tGroup as g1 LEFT JOIN ProjectGroup as pg ON g1.id = pg.tGroup
        LEFT JOIN Project as p ON p.id = pg.Project
        WHERE g1.id = g.id GROUP BY g1.id
    ) as projects 
    FROM tGroup as g;

-- SELEZIONA GLI UTENTI DI UN GRUPPO E IL LORO RUOLO
SELECT u.*,r.Authority FROM User as u, Role as r, GroupRole as gr, tGroup as g WHERE g.id = :id AND r.id = gr.Roleid AND gr.Userid = u.id AND gr.Groupid = g.id;

-- SELEZIONA I PROGETTI DI UN GRUPPO
SELECT p.* FROM Project as p, projectgroup as pg WHERE pg.Project = p.id AND pg.tGroup = :id;

-- SELEZIONA IL RUOLO DI UNO SPECIFICO UTENTE IN UNO SPECIFICO GRUPPO
SELECT r.* FROM Role as r, GroupRole as gr, tGroup as g WHERE r.id = gr.Roleid AND g.id = gr.Groupid AND g.id = :group AND gr.Userid = :user;

-- SELEZIONA I GRUPPI DI UN PROGETTO
SELECT g.* FROM tGroup as g, Project as p, ProjectGroup as pg WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=:projid;

------------------------------------- TASKS -----------------------------------------

-- SELEZIONA TUTTE LE TASK E CONTA QUANTE TASK SONO STATE ASSOCIATE
SELECT t.*, (SELECT COUNT(*) FROM tasklist as tl, tList as l WHERE l.TaskList = tl.id AND tl.Task = t.id) as TaskListCount FROM task as t;

------------------------------------- USERS -----------------------------------------

-- SELEZIONA TUTTI GLI UTENTI E IL NUMERO DI PROGETTI A CUI PARTECIPANO
SELECT u.*,(
    SELECT COUNT( DISTINCT p.id) 
    FROM User as u1,Project as p,ProjectGroup as pg, GroupRole as gr 
    WHERE p.id = pg.Project AND gr.Groupid = pg.tGroup AND u.id = gr.Userid AND u1.id = u.id
    ) as projects FROM User as u;

-- SELEZIONA TUTTI I PROGETTI DI UNO SPECIFICO UTENTE
SELECT DISTINCT p.* FROM User as u,Project as p,ProjectGroup as pg, GroupRole as gr WHERE p.id = pg.Project AND gr.Groupid = pg.tGroup AND u.id = gr.Userid AND u.id=:Userid;

-- SELEZIONA TUTTE LE TASK DI UNO SPECIFICO UTENTE, LA QUERY HA 2 OR SICCOME CI SONO 3 MODI PER CUI UN UTENTE PUO ESSERE ASSEGNATO A UNA TASK GLI PUO ESSERE STATA ASSEGNATA OPPURE E STATA ASSEGNATA AD UN GRUPPO DEL QUALE LUI FA PARTE OPPURE GLI PUO ESSERE STATA CONDIVISA
SELECT t.*, IFNULL(st.id,0) as Condivisa FROM User as u, Assignee as a,Grouprole as gr, tGroup as g, Task as t LEFT JOIN SharedTask as st ON t.id = st.Task WHERE u.id = :userid AND ((a.User = u.id AND t.Assignee = a.id) OR (t.Assignee = a.id AND u.id = gr.Userid AND a.tGroup = g.id AND gr.Groupid = g.id) OR (st.User = u.id AND t.id = st.Task)) GROUP BY t.id;

-- SELEZIONA GLI UTENTI DI UNO SPECIFICO PROGETTO
SELECT DISTINCT u.* FROM User as u, projectgroup as pg, Project as p, tGroup as g, GroupRole as gr WHERE p.id = pg.Project AND pg.tGroup = g.id AND gr.Userid = u.id AND gr.Groupid = g.id AND p.id=:projectid;

-- SELEZIONA I GRUPPI DI UNO SPECIFICO UTENTE
SELECT g.* FROM User as u, tGroup as g, GroupRole as gr WHERE gr.Userid = u.id AND gr.Groupid = g.id AND u.id = :userid;