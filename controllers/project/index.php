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
            }
            try {
                $project->save();
            } catch (Throwable $th) {
                $this->redirect('errors');
                exit;
            }
            $this->redirect('projectcontroller','show',['id'=>$project->id],"GET");
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        */
        public function addForm(){
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/project_form.php';
            $body->model = [ "user" => $this->UserAuth->getCurrentUser()];
            $this->render(L::project_formadd,$body);
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
            array_push($users,['user'=>$project->Creatore,'role'=>'Creatore']);
            $body = new template\PageModel();
            $body->templateFile = '/templates/project/show_project.php';
            $body->model = [ 
                "project" => $project,
                "users" => $users
            ];
            $body->resources = ['header' => ['stylesheet' => "project.css"]];
            $this->render(L::project_show($project->Nome),$body); 
        }

        public function edit(){
            $projectid = "";
            if(isset($this->params['id'])){
                $projectid = $this->params['id'];
                $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$this->params['id']]);
                if($project){
                    $body = new template\PageModel();
                    $body->templateFile = '/templates/forms/project_form.php';
                    $body->model = array(
                        "project" => $project,
                    );
                    $this->render("Form Testing",$body);
                }
            }
            $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
        }

    }

    require_once(ROOT."/private/Controller.php");
?>
