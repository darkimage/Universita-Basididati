<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class projectsController extends Controller{
        public $APIerrors;
        public $UserAuth;

        public function index(){}

        public function getGroups(){
            $groups = tGroup::findAll("SELECT * FROM @this");
            $this->json($groups);
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function getProjectGroups(){
            $projectid = null;
            if(!isset($this->params['id']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['id'];
            $groups = tGroup::findAll("SELECT g.id,g.Nome FROM tGroup as g, Project as p, ProjectGroup as pg 
                WHERE p.id = pg.Project AND pg.tGroup = g.id AND p.id=:projid",['projid'=>$projectid]);
            $this->json($groups);
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function addProjectGroup(){
            $projectid = null;
            $groupid = null;
            if(!isset($this->params['project']) || !isset($this->params['group']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['project'];
            $groupid = $this->params['group'];
            $projectgroup = new ProjectGroup(['Project',$projectid],['tGroup',$groupid]);
            try {
                $projectgroup->save();
            } catch (Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
            $this->json($projectgroup);
        }

        /**
        * @service pre bool UserAuth->getCurrentUser()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @service post void APIerrors->json($this->notauth())
        */
        public function removeProjectGroup(){
            $projectid = null;
            $groupid = null;
            if(!isset($this->params['project']) || !isset($this->params['group']))
                $this->json($this->APIerrors->notfound());
            $projectid = $this->params['project'];
            $groupid = $this->params['group'];
            try {
                $res = ProjectGroup::find("DELETE FROM @this WHERE tGroup=:groupid AND Project=:projectid",['groupid'=>$groupid,'projectid'=>$projectid],[]);
                if($res)
                    $this->json($this->APIerrors->success());
            } catch (\Throwable $th) {
                $this->json($this->APIerrors->servererror());
            }
        }
    }

    require_once(ROOT."/private/Controller.php");

?>
    