<?php
    if(!defined('ROOT')){
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/ControllerLogic.php");
    require_once(ROOT."/private/i18n.php");

    class userController extends Controller{
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
            $columns = isset($this->params['columns']) ? (int)$this->params['columns'] : 2;
            if(!isset($this->params['columns'])) $this->params['columns'] = $columns;

            $usersCount = (int)User::find("SELECT Count(id) as count FROM @this")->count;
            $users = User::findAll("SELECT u.*,(SELECT COUNT( DISTINCT p.id) FROM User as u1,Project as p,ProjectGroup as pg, GroupRole as gr WHERE p.id = pg.Project AND gr.Groupid = pg.tGroup AND u.id = gr.Userid AND u1.id = u.id) as projects FROM User as u",null,['max'=> $max,'offset'=>$offset]);
            $body = new template\PageModel();
            $body->templateFile = '/templates/user/list_users.php';
            $body->model = [
                "users" => $users,
                "usersCount" => $usersCount,
                "params" => $this->params
            ];
            $body->addToModel($this->params);
            $body->resources = ['header' => ['stylesheet' => "project.css"]];
            $this->render(L::user_list,$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAnyAuths("USER","ADMIN","SUPERADMIN")
        * @method post void redirect("errors","notauth")
        */
        public function show(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",["error"=>L::user_notspecified]);
            $auth = false;
            $userid = $this->params['id'];
            $user_auth = $this->UserAuth->getCurrentUser();
            $user = null;
            $projects = null;
            $groups = null;
            try {
                $user = User::find("SELECT * FROM @this WHERE id=:userid",['userid'=>$userid]);
                $projects = Project::findAll("SELECT DISTINCT p.* FROM User as u,Project as p,ProjectGroup as pg, GroupRole as gr WHERE p.id = pg.Project AND gr.Groupid = pg.tGroup AND u.id = gr.Userid AND u.id=:userid;",['userid'=>$userid]);
                $groups = tGroup::findAll("SELECT g.* FROM User as u, tGroup as g, GroupRole as gr WHERE gr.Userid = u.id AND gr.Groupid = g.id AND u.id = :userid;",['userid'=>$userid]);
                $tasks = Task::findAll("SELECT t.*, IFNULL(st.id,0) as Condivisa FROM User as u, Assignee as a,Grouprole as gr, tGroup as g, Task as t LEFT JOIN SharedTask as st ON t.id = st.Task WHERE u.id = :id AND ((a.User = u.id AND t.Assignee = a.id) OR (t.Assignee = a.id AND u.id = gr.Userid AND a.tGroup = g.id AND gr.Groupid = g.id) OR (st.User = u.id AND t.id = st.Task)) GROUP BY t.id",['id'=>$userid]);
            } catch (Throwable $th) {
                $this->redirect('errors');
            }

            if($user_auth->id != $user->id){
                if($this->UserAuth->UserHasAnyAuths("ADMIN","SUPERADMIN"))
                    $auth = true;
            }else $auth = true;

            if(!$auth)
                $this->redirect("errors","notauth");
            

            $body = new template\PageModel();
            $body->templateFile = '/templates/user/show_user.php';
            $body->model = [ 
                "user" => $user,
                "projects" => $projects,
                "groups" => $groups,
                "tasks" => $tasks,
                "authorized" => $auth
            ];
            $body->resources = ['header'=>['stylesheet'=>'user.css']];
            $this->render(L::user_show($user->Nome),$body);
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors")
        */
        public function process(){
            if($this->params['update'] == 'false' && !$this->UserAuth->UserHasAuth("SUPERADMIN"))
                $this->redirect("errors","notauth");
            $user = User::fromData($this->params);
            if($user){
                if($this->params['update'] == 'true'){
                    if($user->Password){
                        $user->Password = password_hash($user->Password);
                    }else{
                        $userdb = User::find("SELECT * FROM @this WHERE id=:id",['id'=>$user->id]);
                        $user->Password = $userdb->Password;
                    }
                }else{
                    $user->Password = password_hash($user->Password);
                }
                try {
                    $user->save();
                } catch (\Throwable $th) {
                    $this->redirect("errors");
                }
            }
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @service pre bool UserAuth->UserHasAuth("SUPERADMIN")
        * @method post void redirect("errors")
        */
        public function add(){
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/user_form.php';
            $this->render(L::user_add,$body); 
        }


        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors")
        */
        public function edit(){
            if(!isset($this->params['id']))
                $this->redirect("errors","index",['error'=>L::user_notfound]);
            $user = null;
            $userid = $this->params['id'];
            try {
                $user = User::find("SELECT * FROM @this WHERE id=:id",['id'=>$userid]);
                if(!$user)
                    $this->redirect("errors","index",['error'=>L::user_notfound]);
                if(!$this->UserAuth->UserHasAuth("SUPERADMIN")){
                    if(!$this->UserAuth->getCurrentUser()->id == $user->id)
                      $this->redirect("errors","notauth");  
                }
            } catch (\Throwable $th) {
               $this->redirect("errors");
            }
            $body = new template\PageModel();
            $body->templateFile = '/templates/forms/user_form.php';
            $body->model = [ 'update'=>'true','user'=>$user];
            $this->render(L::user_add,$body); 
        }

        /**
        * @service pre bool UserAuth->requireUserLogin()
        * @method post void redirect("errors")
        */
        public function logout(){
            $referee = $_SERVER['HTTP_REFERER'];
            Session::getInstance()->destroy();
            header("location:$referee");
            exit;
        }

    }

    require_once(ROOT."/private/Controller.php");
?>