<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class tasksController extends Controller{
        public $APIerrors;
        public $UserAuth;

        public function index() {}

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function addTaskList(){
            $tasklist = new TaskList(['Completata',0]);
            try {
                $tasklist->save();
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
            $this->json($tasklist);
        }

        
        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function removeTaskList(){
            if(!isset($this->params['id']))
                $this->json($this->APIerrors->notfound());
            $tasklistid = $this->params['id'];
            try {
                $res = tList::findAll("DELETE FROM @this WHERE TaskList=:id",['id'=>$tasklistid]);
                if(!$res) 
                    $this->json($this->APIerrors->servererror());
                $res = TaskList::findAll("DELETE FROM @this WHERE id=:id",['id'=>$tasklistid]);
                if(!$res) 
                    $this->json($this->APIerrors->servererror());
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
            $this->json($this->APIerrors->success());
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function getTasksOfProject(){
            if(!isset($this->params['id']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['id'];
            try {
                $tasks = Task::findAll("SELECT * FROM @this WHERE Project=:id",['id'=>$projectid]);
                $this->json($tasks);
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function addTaskToList(){
            if(!isset($this->params['id']) || !isset($this->params['task']) )
                $this->json($this->APIerrors->notfound());
            $tasklistid = $this->params['id'];
            $taskid = $this->params['task'];
            try {
                $tList = new tList(['Task',$taskid],['TaskList',$tasklistid]);
                $tList->save();
                $this->json($tList);
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function removeTaskFromList(){
            if(!isset($this->params['id']) || !isset($this->params['task']) )
                $this->json($this->APIerrors->notfound());
            $tasklistid = $this->params['id'];
            $taskid = $this->params['task'];
            try {
                $res = tList::findAll("DELETE FROM @this WHERE Task=:tid AND TaskList=:tlid",['tid'=>$taskid , 'tlid'=>$tasklistid]);
                if(!$res)
                    $this->json($this->APIerrors->servererror());
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
        }


    }

    require_once(ROOT."/private/Controller.php");

?>
    