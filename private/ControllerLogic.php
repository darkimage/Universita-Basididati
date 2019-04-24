<?php 
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/services.php");
    require_once(ROOT."/private/utils.php");

    class AnnotatedMethod {
        private $name;
        private $caller;
        private $args = [];
        private $refl_method;
        private $type;

        function __construct(String $name, Object $caller, String $args, String $type){
            $this->name = $name;
            $this->caller = $caller;
            $this->type = $type;
            $this->args = $this->evalArgs($args);
            $this->refl_method = new ReflectionMethod(get_class($caller), $name);
        }

        private function evalArgs($args){
            $arguments = [];
            $count = preg_match_all("/{([^\}]+)}|[^\,]+/",$args,$matches,PREG_SET_ORDER);
            if($count){
                foreach ($matches as $key=>$value) {
                    $arguments[$key] = eval("return ". ((count($value)-1) ? $value[1] : $value[0]) .";?>");
                }
            }
            return $arguments;
        }

        function call(){
            if($this->type == "void"){
                $this->refl_method->invoke($this->caller, ...$this->args);
                return true;
            }else
                return $this->refl_method->invoke($this->caller, ...$this->args);
        }
    }

    class ServiceMethod extends AnnotatedMethod{
        function __construct(String $name, Object $caller, String $args, String $type){
            parent::__construct(explode("->",$name)[1],$caller->{explode("->",$name)[0]},$args,$type);
        }
    }

    class ControllerDecorator {
        private $reflection;
        private $controller;

        function __construct($controller){
            $this->controller = $controller;
            $this->reflection = new ReflectionClass(get_class($controller));
            foreach (Services::getInstance()->services as $key => $value) {
                if($this->reflection->hasProperty($key)){
                    $this->controller->$key = Services::getInstance()->$key;
                }
            }
        }

        public function __call(string $name , array $arguments){
            $this->controller->setParams();
            $method = $this->reflection->getMethod($name);
            $doc = $method->getDocComment();
            $annotation = $this->processAnnotation($doc);

            //Eseguiamo le PRE annotation e controlliamo che ritornino un valore true (o comunque valido)
            $exec = true;
            foreach ($annotation["pre"] as $value) {
                $exec = $value->call();
                if(!$exec) break;
            }
            //Eseguiamo il metodo chiamato se uno degli 
            if($exec)
                $this->controller->$name(...$arguments);
            //Eseguiamo le POST SOLO SE LE PRE FALLISCONO
            else
                foreach ($annotation["post"] as $value) {
                    $value->call();
                }
        }

        private function processAnnotation(String $doc){
            $regex = "/@(service|method)\s(pre|post)\s(bool|void)\s([^\s\(]+)\((.*)\)/";
            $count = preg_match_all($regex, $doc,$matches);
            $annotations = ["post" => [], "pre"=>[]];
            for ($i=0; $i < $count ; $i++) {
                $params = [$matches[4][$i],$this->controller,$matches[5][$i],$matches[3][$i]];
                $method = null;
                if($matches[1][$i] == "method"){
                    $method = new AnnotatedMethod(...$params);
                }else{
                    $method = new ServiceMethod(...$params);
                }
                array_push($annotations[$matches[2][$i]], $method);
            }
            return $annotations;
        }

        public function serve(){
            if(isset($_GET['action'])){
                $method = $this->reflection->getMethod($_GET['action']);
                if($method->isPublic()){
                    $this->{$_GET['action']}();
                }
            }else{
                $this->index();
            }
        }
    }

    abstract class Controller{
        protected $params = [];

        public function setParams(){
            $this->params = $this->getCurrentParams();
        }

        abstract public function index();

        public function render(String $title,template\PageModel $pageModel){
            $mainPage = new template\PageModel();
            $mainPage->title = $title;
            $mainPage->resources = $pageModel->resources;
            $mainPage->body = $pageModel->setUpTemplate();
            $mainPage->render();
            exit;
        }

        public function redirect(String $controller,String $action="index",$params=[],$type="SESSION"){
            // $params['referee'] = $_SERVER['REQUEST_URI'];
            if($type == "GET"){
                $query = '';
                foreach ($params as $key => $value) {
                    $query .= $key."=".$value;
                    if($key != array_key_last($params)){
                        $query .= '&';
                    }
                }
                if($query)
                    $query = "&".$query;
                header("location: /".$controller."?action=".$action.$query);
                exit;
            }else if($type == "SESSION"){
                $sessionParams = [];
                foreach ($params as $key => $value) {
                    $sessionParams[$key] = $value;
                }
                Session::getInstance()->params = $sessionParams;
                header("location: /".$controller."?action=".$action);
                exit;
            }
        }

        //Prende i parametri da richieste GET,POST e in SESSION['params']
        protected function getCurrentParams(){
            unset($this->params);
            $query = $_SERVER['QUERY_STRING'];
            $paramsArray = explode('&',$query);
            $params = [];
            for ($i=0; $i < count($paramsArray); $i++) {
                $keys = explode("=",$paramsArray[$i]);
                if($keys[0]){
                    $params[$keys[0]] = $keys[1];
                }
            }
            foreach ($_POST as $key => $value) {
                $params[$key] = $value;
            }
            $session = Session::getInstance();
            if(isset($session->params)){
                foreach ($session->params as $key => $value) {
                    $params[$key] = $value;
                }
            }
            unset($session->params);
            return $params;
        }
    }
?>