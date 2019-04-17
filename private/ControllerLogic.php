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

    class AnnotatedExpression {
        private $data;
        private $caller;
        function __construct(String $data, Object $caller){

        }
    }

    class ControllerDecorator {
        private $reflection;
        private $controller;

        function __construct($controller){
            $this->controller = $controller;
            $this->reflection = new ReflectionClass(get_class($controller));
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
            //Eseguiamo il metodo chiamato
            if($exec)
                $this->controller->$name(...$arguments);
            //Eseguiamo le POST annotation non ci interessa se ritornano vero o falso
            foreach ($annotation["post"] as $value) {
                $value->call();
            }
        }

        private function processAnnotation(String $doc){
            $regex = "/@(method|expression)\s(pre|post)\s([^\s]+)=>({(.+)}|([^{}\s;\(]+)\(([^\)]*)\))/";
            $count = preg_match_all($regex, $doc,$matches);
            $annotations = ["post" => [], "pre"=>[]];
            for ($i=0; $i < $count ; $i++) { 
                $method = new AnnotatedMethod($matches[6][$i],$this->controller,$matches[7][$i],$matches[3][$i]);
                array_push($annotations[$matches[2][$i]], $method);
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
        public function test(String $test1, String $test2){
            return $test1.$test2;
        }

        protected function needUserSession($authClosure){
            $Userauth = Services::getInstance()->UserAuth;
            if($Userauth->requireUserLogin()){
                return false;
            }
            return $authClosure();
        }

        abstract public function index();
    }

    class testClass extends Controller{
        /**
         * Undocumented function
         *
         * @return void
         */
         function test1(){

         }

        /**
         * Undocumented function
         * abc
         * @method pre void=>test("test1","test2")
         * @method post bool=>test1()
         * 
         */
        public function index(){

        }
    }




?>