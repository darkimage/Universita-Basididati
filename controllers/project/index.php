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

        public function index(){

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

        public function show(){
            $projectid = "";
            if(isset($this->params['id'])){
                $projectid = $this->params['id'];
                $project = Project::find("SELECT * FROM @this WHERE id=:id",["id"=>$this->params['id']]);
                if($project){
                    print_r($project);
                    exit;
                }
            }
            $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
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
