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
            if($args){
                $args = preg_replace("/\"/","",$args);
                $this->args = explode(",", $args);
            }
            $this->refl_method = new ReflectionMethod(get_class($caller), $name);
        }

        function call(){
            if($this->type == "void"){
                $this->refl_method->invoke($this->caller, ...$this->args);
                return true;
            }else
                return $this->refl_method->invoke($this->caller, ...$this->args);
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
            $regex = "/@method\s(pre|post)\s(bool|void)\s([^\s\(]+)\(([^\)]*)\)/";
            $count = preg_match_all($regex, $doc,$matches);
            $annotations = ["post" => [], "pre"=>[]];
            for ($i=0; $i < $count ; $i++) {
                $method = new AnnotatedMethod($matches[3][$i],$this->controller,$matches[4][$i],$matches[2][$i]);
                array_push($annotations[$matches[1][$i]], $method);
            }
            return $annotations;
        }

        public function serve(){
            if(isset($_GET)){
                $this->$_GET['action']();
            }else{
                $this->index();
            }
        }
    }

    abstract class Controller{
        protected $params = [];

        public function __construct(){
            $this->params = $this->getCurrentParams();
        }

        abstract public function index();

        public function render(String $title,template\PageModel $pageModel){
            $mainPage = new template\PageModel();
            $mainPage->title = $title;
            $mainPage->resources = $pageModel->resources;
            $mainPage->body = $pageModel->setUpTemplate();
            $mainPage->render();
        }

        public function redirect(String $controller,String $action="index",$params=[]){
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
        }

        protected function getCurrentParams(){
            $query = $_SERVER['QUERY_STRING'];
            $paramsArray = explode('&',$query);
            $params = [];
            for ($i=0; $i < count($paramsArray); $i++) {
                $keys = explode("=",$paramsArray[$i]);
                $params[$keys[0]] = $keys[1];
            }
            return $params;
        }
    }
?>