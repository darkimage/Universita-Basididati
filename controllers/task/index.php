<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/ControllerLogic.php");

    class taskController extends Controller{
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

            $tasksCount = (int)Task::find("SELECT Count(id) as count FROM @this")->count;
            $tasks = Task::findAll("SELECT t.*, (SELECT COUNT(*) FROM tasklist as tl, tList as l WHERE l.TaskList = tl.id AND t.TaskList = tl.id) as TaskListCount FROM task as t",null,['max'=> $max,'offset'=>$offset]);
            $body = new template\PageModel();
            $body->templateFile = '/templates/task/list_task.php';
            $body->model = [
                "tasks" => $tasks,
                "tasksCount" => $tasksCount,
                "params" => $this->params
            ];
            $this->render(L::task_list,$body);
        }

        public function process(){
            $task = Task::fromData($this->params);
            try {
                $task->save();
            } catch (Throwable $th) {
                $this->redirect('errors');
            }
            $this->redirect('project','show',['id'=>$task->id],"GET");
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function add(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::project_notspecified]);
            $projectid = $this->params['id'];
            $project = Project::find("SELECT * FROM @this WHERE id=:id",['id'=>$projectid]);
            if(!$project)
                $this->redirect("errors","index",["error"=>L::project_notfound($projectid)]);
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/task_form.php';
            $body->model = [ "user" => $this->UserAuth->getCurrentUser(), "project"=>$project];
            $body->resources = ['body'=> ['script'=>'task.js']];
            $this->render(L::task_add,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function edit(){
            if(!isset($this->params['project']) || !isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::project_notspecified]);
            $projectid = $this->params['project'];
            $taskid = $this->params['id'];
            $task = null;
            try {
                $task = Task::find("SELECT * FROM @this WHERE id=:id AND Project = :pid",['id'=>$taskid,'pid'=>$projectid]);
                
                $auth = ($this->UserAuth->getCurrentUser()->id == $task->User->id);
                if(!$auth)
                    $this->redirect("errors","index",["error"=>L::error_notauth]);

            } catch (\Throwable $th) {
                $this->redirect('errors');
            }
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/task_form.php';
            $body->model = [ "user" => $this->UserAuth->getCurrentUser(), "project"=>$task->Project, "task"=> $task , "update" => 'true'];
            $body->resources = ['body'=> ['script'=>'task.js']];
            $this->render(L::task_edit,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function show(){

        }
    }

    require_once(ROOT."/private/Controller.php");

?>
