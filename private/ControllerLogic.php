<?php 
	if(!defined('ROOT')){
		define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	}
    require_once(ROOT."/private/template_file.php");
    require_once(ROOT."/private/session.php");
    require_once(ROOT."/private/services.php");
    require_once(ROOT."/private/utils.php");

    class Method {
        public $name;
        public $arguments;

        function __construct(String $name,$arguments){
            $this->name = $name;
            $this->arguments = $arguments;
        }
    }

    class AnnotatedMethod extends Method{
        public $pre;
        public $post;

        function __construct(String $name,$arguments,$pre,$post){
            parent::__construct($name,$arguments);
        }

        function call(){
            
        }
    }

    class ControllerDecorator {
        private $reflection;
        private $controller;

        function __construct($controller){
            $this->controller = $controller;
            $this->reflection = new ReflectionClass(get_class($controller));
            // $methods = $reflection->getMethods();
            // $docs = [];
            // foreach ($methods as $method) {
            //     $doc = $method->getDocComment());                
            // }
            // $test = $reflection->getMethod('test1')->getDocComment();
        }

        public function __call(string $name , array $arguments){
            $method = $this->reflection->getMethod($name);
            $doc = $method->getDocComment();
            $annotation = $this->processAnnotation($doc);
        }

        private function processAnnotation(String $doc){
            $regex = "/@(method|expression)\s(pre|post)\s([^\s]+)=>({(.+)}|[^{}\s;]+)/sm";
            $count = preg_match_all($regex, $doc,$matches);
            $annotations = ['pre' => [],'post' => []];
            for ($i=0; $i < $count ; $i++) { 
                if($matches[1][i] == "method"){
                    if($matches[3][i] == "void"){
                        array_push($annotations[$matches[2][i]],
                    }else{

                    }
                }else{

                }
            }
        }

        public function serve(){
            if(isset($_GET)){
                $this->$_GET['action']();
            }else{
                $this->index();
            }
        }
    }

    class Controller{
        /**
         * Undocumented function
         *
         * @method pre void=>test()
         * @method post bool=>test1()
         * 
         */
        public function index(){

        }

        public function test(){

        }

        protected function needUserSession($authClosure){
            $Userauth = Services::getInstance()->UserAuth;
            if($Userauth->requireUserLogin()){
                return false;
            }
            return $authClosure();
        }
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
         * @method pre void=>test()
         * @method post bool=>test1()
         * 
         */
        public function index(){

        }
    }


?>