<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class usersController extends Controller{
        public $APIerrors;
        public $UserAuth;

        public function index(){}

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function getUsersOfProject(){
            if(!isset($this->params['id']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['id'];
            $users = User::findAll("SELECT DISTINCT u.* FROM User as u, projectgroup as pg, Project as p, tGroup as g, GroupRole as gr WHERE p.id = pg.Project AND pg.tGroup = g.id AND gr.Userid = u.id AND gr.Groupid = g.id AND p.id=:projid",['projid'=>$projectid]);
            $this->json($users);
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function getGroupsOfProject(){
            if(!isset($this->params['id']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['id'];
            $groups = User::findAll("SELECT g.* FROM tGroup as g, Project as p, ProjectGroup as pg WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=:projid",['projid'=>$projectid]);
            $this->json($groups);
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function createAssignee(){
            if(!isset($this->params['id']) || !isset($this->params['type']) )
                $this->json($this->APIerrors->notfound());
            $id = $this->params['id'];
            $type = $this->params['type'];
            $assignee = new Assignee(['User',($type == 'user') ? $id : null],['tGroup',($type == 'group') ? $id : null]);
            try {
                $assignee->save();
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
            return $this->json($assignee);
        }

    }

    require_once(ROOT."/private/Controller.php");

?>
    