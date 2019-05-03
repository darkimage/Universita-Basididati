SELECT u.id,u.Nome,u.DataNascita,u.NomeUtente,r.Authority FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p WHERE p.id = 1 AND p.id = pg.Project AND pg.tGroup = gr.Groupid AND r.id = gr.Roleid AND gr.Userid = u.id;

DELETE FROM ProjectGroup WHERE tGroup=1 AND Project=2;

SELECT u.id,u.Nome,u.DataNascita,u.NomeUtente,r.Authority,g.Nome as GroupName FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p, tGroup as g WHERE p.id =1 AND p.id = pg.Project AND pg.tGroup = gr.Groupid AND r.id = gr.Roleid AND g.id = pg.tGroup AND gr.Userid = u.id;

SELECT g.id,g.Nome FROM tGroup as g, Project as p, ProjectGroup as pg WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=1;