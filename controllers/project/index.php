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
            $body->addToModel($this->params);
            $body->resources = ['header' => ['stylesheet' => "project.css"]];
            $this->render(L::project_list,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function process(){
            $project = Project::fromData($this->params);
            $auth = ($this->UserAuth->getCurrentUser()->id == $project->Creatore->id);
            if(!$auth)
                $this->redirect("errors","index",["error"=>L::error_notauth]);
            try {
                $project->save();
            } catch (Throwable $th) {
                $this->redirect('errors');
            }
            if($this->params['update'] != 'false'){
                Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::project_updated($project->id)];
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
            } catch (Throwable $th) {
                $this->redirect('errors');
            }

        }


        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function add(){
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
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::project_notspecified]);
            $projectid = $this->params['id'];
            $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$projectid]);
            if(!$project)
                $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
            
            /*data*/
            $users = User::findAll("SELECT u.*,r.Authority,g.Nome as GroupName 
            FROM User as u, Role as r, ProjectGroup as pg,GroupRole as gr, Project as p, tGroup as g 
            WHERE p.id = :projid 
            AND p.id = pg.Project 
            AND pg.tGroup = gr.Groupid 
            AND r.id = gr.Roleid 
            AND g.id = pg.tGroup
            AND gr.Userid = u.id",['projid'=>$projectid]);

            $groups = tGroup::findAll("SELECT g.id,g.Nome FROM tGroup as g, Project as p, ProjectGroup as pg 
            WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=:projid",['projid'=>$projectid]);

            $tasks = Task::findAll("SELECT * FROM @this WHERE Project=:id",['id'=>$projectid]);

            $auth = ($this->UserAuth->getCurrentUser()->id == $project->Creatore->id);
            if(!$auth)
                $auth = $this->UserAuth->UserHasAuth("SUPERADMIN");

            $body = new template\PageModel();
            $body->templateFile = '/templates/project/show_project.php';
            $body->model = [ 
                "project" => $project,
                "users" => $users,
                "groups" => $groups,
                "tasks" => $tasks,
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
                    $groups = tGroup::findAll("SELECT g.id,g.Nome FROM tGroup as g, Project as p, ProjectGroup as pg WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=:projid",['projid'=>$projectid]);
                    $body = new template\PageModel();
                    $body->templateFile = '/templates/forms/project_form.php';
                    $body->model = array(
                        "project" => $project,
                        "groups" => $groups,
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

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function remove(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::project_notspecified]);
            $projectid = $this->params['id'];
            try {
                $project = Project::find("SELECT * FROM @this WHERE id=:id",['id'=> $projectid]);
                if($this->UserAuth->getCurrentUser()->id == $project->Creatore->id || $this->UserAuth->UserHasAuth("SUPERADMIN")){
                    $res = Project::find("DELETE FROM @this WHERE id=:id",['id'=> $projectid]);
                    if($res){
                        Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::project_deleted($projectid)];
                        $this->redirect("project","index");
                    }
                }else{
                    $this->redirect("errors","index",["error"=>L::error_notauth]);
                }
            } catch (\Throwable $th) {
                $this->redirect("errors");
            }
        }

    }

    require_once(ROOT."/private/Controller.php");
?>
