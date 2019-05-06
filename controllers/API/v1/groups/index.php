<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class groupsController extends Controller{
        public $APIerrors;
        public $UserAuth;

        public function index(){}

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function addUserToGroup(){
            if(!isset($this->params['id']) || !isset($this->params['user']) )
                $this->json($this->APIerrors->notfound());
            $groupid = $this->params['id'];
            $userid = $this->params['user'];
            try {
                $group = tGroup::find("SELECT * FROM @this WHERE id=:id",['id'=>$groupid]);
                $role = Role::find("SELECT r.* FROM Role as r, GroupRole as gr, tGroup as g WHERE r.id = gr.Roleid AND g.id = gr.Groupid AND g.id = :group AND gr.Userid = :user",['user'=>$this->UserAuth->getCurrentUser()->id,'group'=>$groupid]);
                if(!$this->UserAuth->UserHasAuth("SUPERADMIN")){
                    if(!$role) 
                        $this->redirect("errors","notauth");
                    if($role->Authority != "CREATORE" || $role->Authority != "MODERATORE")
                        $this->redirect("errors","notauth");
                }
                $grouprole = new GroupRole(['Userid',(int)$userid],['Groupid',(int)$groupid],['Roleid',6]);
                $grouprole->save();
                $this->json($grouprole);
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
        }

    }

    require_once(ROOT."/private/Controller.php");

?>
    