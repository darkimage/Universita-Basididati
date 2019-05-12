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
        * @service post void APIerrors->json($this->notauth())
        */
        public function getUsers(){
            $search = null;
            if(isset($this->params['search'])) $search = $this->params['search'];
            if($search){
                try {
                    $users = User::findAll("SELECT * FROM @this WHERE Nome LIKE :src OR Cognome LIKE :src OR NomeUtente LIKE :src",['src'=> '%'.$search.'%']);
                    $this->json($users);
                } catch (\Throwable $th) {
                    $this->json($this->APIerrors->servererror());
                }
            }else{
                try {
                    $users = User::findAll("SELECT * FROM @this");
                    $this->json($users);
                } catch (\Throwable $th) {
                    $this->json($this->APIerrors->servererror());
                }
            }
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function getUsersOfProject(){
            if(!isset($this->params['id']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['id'];
            try {
                $users = User::findAll("SELECT DISTINCT u.* FROM User as u, projectgroup as pg, Project as p, tGroup as g, GroupRole as gr WHERE p.id = pg.Project AND pg.tGroup = g.id AND gr.Userid = u.id AND gr.Groupid = g.id AND p.id=:projid",['projid'=>$projectid]);
                $this->json($users);
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
           
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
    