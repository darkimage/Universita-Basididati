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
            try {
                $tasksCount = (int)Task::find("SELECT Count(id) as count FROM @this")->count;
                $tasks = Task::findAll("SELECT t.*, (SELECT COUNT(*) FROM tasklist as tl, tList as l WHERE l.TaskList = tl.id AND tl.Task = t.id) as TaskListCount FROM task as t",null,['max'=> $max,'offset'=>$offset]);
                $body = new template\PageModel();
                $body->templateFile = '/templates/task/list_task.php';
                $body->model = [
                    "tasks" => $tasks,
                    "tasksCount" => $tasksCount,
                    "params" => $this->params
                ];
                $body->addToModel($this->params);
                $this->render(L::task_list,$body);
            } catch (\Throwable $th) {
                $this->redirect("errors");
            }
        }

        public function process(){
            $task = Task::fromData($this->params);
            $update = $this->params['update'];
            try {
                $task->save();
            } catch (Throwable $th) {
                $this->redirect('errors');
            }
            if($update != 'true')
                $this->redirect('project','show',['id'=>$task->Project->id],"GET");
            else{
                Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::task_updated($task->id)];
                $this->redirect('task','show',['id'=>$task->id],"GET");
            }
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
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::task_notspecified]);
            $taskid = $this->params['id'];
            $task = null;
            $tList = null;
            $Tasklist = null;
            try {
                $task = Task::find("SELECT * FROM @this WHERE id=:id",['id'=>$taskid]);
                $tList = tList::findAll("SELECT l.* FROM tList as l, TaskList as tl WHERE tl.id=l.TaskList AND tl.Task=:id",['id'=>$task->id]);
                $Tasklist = TaskList::find("SELECT * FROM @this WHERE Task=:id",['id'=>$task->id]);
                $auth = ($this->UserAuth->getCurrentUser()->id == $task->User->id);
                if(!$auth)
                    $this->redirect("errors","index",["error"=>L::error_notauth]);

            } catch (\Throwable $th) {
                $this->redirect('errors');
            }
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/task_form.php';
            $body->model = [ "user" => $this->UserAuth->getCurrentUser(), "project"=>$task->Project, "task"=> $task, 'list'=> $tList, 'tasklist'=> $Tasklist , "update" => 'true'];
            $body->resources = ['body'=> ['script'=>'task.js']];
            $this->render(L::task_edit,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function show(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::task_notspecified]);
            $taskid = $this->params['id'];
            $tList = null;
            $task = null;
            try {
                $task = Task::find("SELECT t.*, IF(st.id=null,null,u.id) as Condivisore, IFNULL(st.id,null) as SharedTask FROM Task as t LEFT JOIN SharedTask as st ON t.id = st.Task LEFT JOIN User as u ON u.id = st.User WHERE t.id=:id",['id'=>$taskid]);
                if($task->Condivisore) 
                    $task->Condivisore = User::find("SELECT * FROM @this WHERE id=:id",['id'=>$task->Condivisore]);
                $tList = tList::findAll("SELECT l.* FROM tList as l, TaskList as tl WHERE tl.id=l.TaskList AND tl.Task=:id",['id'=>$task->id]);
            } catch (\Throwable $th) {
                $this->redirect("errors");
            }
            $auth = ($task->User->id == $this->UserAuth->getCurrentUser()->id);
            if(!$auth)
                $auth = $this->UserAuth->UserHasAuth("SUPERADMIN");
            $body = new template\PageModel();
            $body->templateFile = '/templates/task/show_task.php';
            $body->model = [ "task" => $task, 'list'=> $tList, 'authorized'=>$auth];
            $body->resources = ['body'=> ['script'=>'task.js']];
            $this->render(L::task_show($task->Nome),$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function complete(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::task_notspecified]);
            $taskid = $this->params['id'];
            $task = Task::find("SELECT * FROM @this WHERE id=:id",['id'=>$taskid]);
            $auth = ($task->User->id == $this->UserAuth->getCurrentUser()->id);
            if(!$auth)
                $auth = $this->UserAuth->UserHasAuth("SUPERADMIN");
            if(!$auth)
                $this->redirect("errors","notauth");

            if(!$task)
                $this->redirect("errors","index",["error"=>L::task_notfound($taskid)]);
            $task->Completata = 1;
            $task->DataCompletamento = date("Y-m-d");
            try {
                $task->save();
                Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::task_flashcompleted($task->id)];
                $this->redirect('task','show',['id'=>$task->id],'GET');
            } catch (\Throwable $th) {
                $this->redirect("errors");
            }
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function remove(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::task_notspecified]);
            $taskid = $this->params['id'];
            try {
                $task = Task::find("SELECT * FROM @this WHERE id=:id",['id'=> $taskid]);
                if($this->UserAuth->getCurrentUser()->id == $task->User->id || $this->UserAuth->UserHasAuth("SUPERADMIN")){
                    $res = Task::find("DELETE FROM @this WHERE id=:id",['id'=> $taskid]);
                    if($res){
                        Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::task_deleted($taskid)];
                        $this->redirect("task");
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
