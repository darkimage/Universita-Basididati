<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/dbConnection.php");
    require_once(ROOT."/private/i18n.php");

    class projectController extends Controller{
        public $UserAuth;

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function index(){
            $max = isset($this->params['max']) ? (int)$this->params['max'] : 5;
            if(!isset($this->params['max'])) $this->params['max'] = $max;
            $offset = isset($this->params['offset']) ? (int)$this->params['offset'] : 0;
            if(!isset($this->params['offset'])) $this->params['offset'] = $offset;
            $projectsCount = (int)Project::find("SELECT Count(id) as count FROM @this")->count;
            $projects = Project::findAll("SELECT * FROM @this",null,['max'=> $max,'offset'=>$offset]);
            $body = new template\PageModel();
            $body->templateFile = '/templates/project/list_projects.php';
            $body->model = [
                "projects" => $projects,
                "projectsCount" => $projectsCount,
                "params" => $this->params
            ];
            $body->resources = ['header' => ['stylesheet' => "project.css"]];
            $this->render(L::project_list,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function add(){
            $project = Project::fromData($this->params);
            if($this->params['update'] === 'false'){
                $project->DataInizio = date("Y-m-d");
            }else{
                $projectform = $project;
                $project = Project::find("SELECT * FROM @this WHERE id=:id",['id'=>$project->id]);
                $project->DataScadenza = $projectform->DataScadenza;
                $project->Descrizione = $projectform->Descrizione;
                $project->Nome = $projectform->Nome;
                $auth = ($this->UserAuth->getCurrentUser()->id == $project->Creatore->id);
                if(!$auth)
                    $this->redirect("errors","index",["error"=>L::error_notauth]);
            }
            try {
                $project->save();
            } catch (Throwable $th) {
                $this->redirect('errors');
            }
            $this->redirect('project','show',['id'=>$project->id],"GET");
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function complete(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::project_notspecified]);
            $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$this->params['id']]);
            if(!$project)
                $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
            $auth = ($this->UserAuth->getCurrentUser()->id == $project->Creatore->id);
            if(!$auth)
                $this->redirect("errors","index",["error"=>L::error_notauth]);

            try {
                $project->Completato = 1;
                $project->DataCompletamento = date("Y-m-d");
                $project->save();
                Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::project_flashcompleted($project->id)];
                $this->redirect('project','show',['id'=>$project->id],'GET');
            } catch (\Throwable $th) {
                $this->redirect('errors');
            }

        }


        /**
        * @service pre bool UserAuth->requireUserLogin()
        */
        public function addForm(){
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/project_form.php';
            $body->model = [ "user" => $this->UserAuth->getCurrentUser()];
            $this->render(L::project_add,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function show(){
            $projectid = "";
            $users = [];
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::project_notspecified]);
            $projectid = $this->params['id'];
            $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$this->params['id']]);
            if(!$project)
                $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
            
            /*data*/
            $users = User::findAll("SELECT u.id,u.Nome,u.DataNascita,u.NomeUtente,r.Authority 
            FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p 
            WHERE p.id = :projid 
            AND p.id = pg.Project 
            AND pg.tGroup = gr.Groupid 
            AND r.id = gr.Roleid 
            AND gr.Userid = u.id",['projid'=>$projectid]);

            $groups = tGroup::findAll("SELECT g.id,g.Nome FROM tGroup as g, Project as p, ProjectGroup as pg 
            WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=:projid",['projid'=>$projectid]);

            $auth = ($this->UserAuth->getCurrentUser()->id == $project->Creatore->id);

            $body = new template\PageModel();
            $body->templateFile = '/templates/project/show_project.php';
            $body->model = [ 
                "project" => $project,
                "users" => $users,
                "groups" => $groups,
                "authorized" => $auth
            ];
            $body->resources = [
                'header' => ['stylesheet' => "project.css"],
                'body' => ['script' => 'project.js']
            ];
            $this->render(L::project_show($project->Nome),$body); 
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function edit(){
            $projectid = "";
            if(isset($this->params['id'])){
                $projectid = $this->params['id'];
                $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$this->params['id']]);
                if($this->UserAuth->getCurrentUser()->id != $project->Creatore->id)
                    $this->redirect("errors","index",["error"=>L::error_notauth]);
                if($project){
                    $body = new template\PageModel();
                    $body->templateFile = '/templates/forms/project_form.php';
                    $body->model = array(
                        "project" => $project,
                        "user" => $this->UserAuth->getCurrentUser(),
                        "update" => 'true'
                    );
                    $body->resources = [
                        'header' => ['stylesheet' => "project.css"],
                        'body' => ['script' => 'project.js']
                    ];
                    $this->render(L::project_edit,$body);
                }
            }
            $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
        }

    }

    require_once(ROOT."/private/Controller.php");
?>
