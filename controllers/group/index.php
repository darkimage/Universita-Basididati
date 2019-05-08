<?php
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/ControllerLogic.php");

    class groupController extends Controller{
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

            $groupsCount = (int)tGroup::find("SELECT Count(id) as count FROM @this")->count;
            $groups = tGroup::findAll("SELECT g.*,(SELECT COUNT(gr.id) FROM tGroup as g2 LEFT JOIN GroupRole as gr 
            ON g2.id = gr.Groupid WHERE g2.id = g.id GROUP BY g2.id) as users, 
            (SELECT COUNT(p.id) FROM tGroup as g1 LEFT JOIN ProjectGroup as pg ON g1.id = pg.tGroup
            LEFT JOIN Project as p ON p.id = pg.Project WHERE g1.id = g.id GROUP BY g1.id) as projects 
            FROM tGroup as g",null,['max'=> $max,'offset'=>$offset]);
            $body = new template\PageModel();
            $body->templateFile = '/templates/group/list_groups.php';
            $body->model = [
                "groups" => $groups,
                "groupsCount" => $groupsCount,
                "params" => $this->params
            ];
            $body->addToModel($this->params);
            $this->render(L::group_list,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function show(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::group_notspecified]);
            $groupid = $this->params['id'];
            $group = tGroup::find("SELECT * FROM @this WHERE id=:id",["id"=>$groupid]);
            if(!$group)
                $this->redirect("errors","index",["error"=>L::group_notfound($groupid)]);
            
            $users = User::findAll("SELECT u.*,r.Authority FROM User as u, Role as r, GroupRole as gr, tGroup as g WHERE g.id = :id AND r.id = gr.Roleid AND gr.Userid = u.id AND gr.Groupid = g.id",['id'=>$groupid]);
            $projects = Project::findAll("SELECT p.* FROM Project as p, projectgroup as pg WHERE pg.Project = p.id AND pg.tGroup = :id",['id'=>$groupid]);

            $auth = false;
            foreach ($users as $user) {
                if($this->UserAuth->getCurrentUser()->id == $user->id && 
                ($user->Authority == "CREATORE" || $user->Authority == "MODERATORE"))
                    $auth = true;
            }
            if(!$auth)
                $auth = $this->UserAuth->UserHasAuth("SUPERADMIN");

            $body = new template\PageModel();
            $body->templateFile = '/templates/group/show_group.php';
            $body->model = [ 
                "group" => $group,
                "users" => $users,
                "authorized" => $auth,
                "projects" => $projects
            ];
            $body->resources = [
                'header'=>['stylesheet'=>'group.css'],
                'body'=>['script'=>'group.js']
            ];
            $this->render(L::group_show($group->Nome),$body); 
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function process(){
            $group = tGroup::fromData($this->params);
            try {
                $group->save();
                if($group){
                    if($this->params['update'] == 'false'){
                        $user = $this->UserAuth->getCurrentUser();
                        $grouprole = new GroupRole(['Userid',$user->id],['Groupid',$group->id],['Roleid',4]);
                        $grouprole->save();
                        if(!$grouprole) {
                            tGroup::find("DELETE FROM @this WHERE id=:id",['id'=>$group->id]);
                            $this->redirect('errors');
                        }
                    }
                }else{
                    $this->redirect('errors');
                }
            } catch (\Throwable $th) {
                $this->redirect('errors');
            }
            if($this->params['update'] == 'true')
                Session::getInstance()->flash = ['class'=>'alert-success','message'=>L::group_edited($group->id)];
            else
                Session::getInstace()->flash = ['class'=>'alert-success','message'=>L::group_added($group->id)];
            $this->redirect('group','show',['id'=>$group->id],"GET");
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function add(){
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/group_form.php';
            $this->render(L::group_add,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function edit(){
            if(!isset($this->params['id']))
                $this->redirect("errors",'index',['error'=>L::group_notfound($this->params['id'])]);
            $groupid = $this->params['id'];
            try {
                $group = tGroup::find("SELECT * FROM @this WHERE id=:id",['id'=>$groupid]);
                $role = Role::find("SELECT r.* FROM Role as r, GroupRole as gr, tGroup as g WHERE r.id = gr.Roleid AND g.id = gr.Groupid AND g.id = :group AND gr.Userid = :user",['user'=>$this->UserAuth->getCurrentUser()->id,'group'=>$groupid]);
                if(!$this->UserAuth->UserHasAuth("SUPERADMIN")){
                    if(!$role) 
                        $this->redirect("errors","notauth");
                    if($role->Authority != "CREATORE" || $role->Authority != "MODERATORE")
                        $this->redirect("errors","notauth");
                }

                $body = new template\PageModel();
                $body->templateFile = '/templates/forms/group_form.php';
                $body->model = array(
                    "group" => $group,
                    "update" => 'true'
                );
                $this->render(L::group_edit,$body);
            } catch (\Throwable $th) {
                $this->redirect('errors');
            }
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors","notauth")
        */
        public function delete(){
            if(!isset($this->params['id']))
                $this->redirect("errors",'index',['error'=>L::group_notfound($this->params['id'])]);
            $groupid = $this->params['id'];
            $role = Role::find("SELECT r.* FROM Role as r, GroupRole as gr, tGroup as g WHERE r.id = gr.Roleid AND g.id = gr.Groupid AND g.id = :group AND gr.Userid = :user",['user'=>$this->UserAuth->getCurrentUser()->id,'group'=>$groupid]);
            if(!$this->UserAuth->UserHasAuth("SUPERADMIN")){
                if(!$role) 
                    $this->redirect("errors","notauth");
                if($role->Authority != "CREATORE")
                    $this->redirect("errors","notauth");
            }
            try {
                $res = tGroup::find("DELETE FROM @this WHERE id=:id",['id'=>$groupid]);
                if(!$res)
                    $this->redirect('errors');
            } catch (\Throwable $th) {
                $this->redirect('errors');
            }
        }

    }

    require_once(ROOT."/private/Controller.php");

?>
