<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT."/templates/template_file.php");

$libxmlErrors = libxml_use_internal_errors(true);

// class htmlTag{
//     public $tags = array();
//     public $doc = NULL;
//     public $containerTagName = NULL;
//     public $template = NULL;

//     public function render(){
//     }
// }


// $doc = new DOMDocument();
// $libxmlErrors = libxml_use_internal_errors(true);
// $doc->preserveWhiteSpace = FALSE;
// $test = file_get_contents('testhtml.html');
// $doc->loadHTML($test);


// $nodes = $doc->getElementsByTagName('a');
// $oldNode = $nodes[0];

function replaceNode(DOMNode $oldNode, DOMNode $newNode){
    if ($oldNode->hasChildNodes()) {
        $children = [];
        foreach ($oldNode->childNodes as $child) {
            $children[] = $child;
        }
        foreach ($children as $child) {
            $newNode->appendChild($child->parentNode->removeChild($child));
        }
    }

    $oldNode->parentNode->replaceChild($newNode, $oldNode);
    return $newNode;
}

//replaceNode($oldNode,$doc->createElement('div'));

// showDOMNode($doc,$doc);

// function showDOMNode(DOMNode $domNode,DOMDocument $doc) {
//     foreach ($domNode->childNodes as $node)
//     {
//         if($node->nodeName == 't-testing'){
//             replaceNode($node,$doc->createElement('div'));
//         }
//         if($node->hasChildNodes()) {
//             showDOMNode($node,$doc);
//         }
//     }    
// }

// echo htmlspecialchars($doc->saveHTML());

$doc = new DOMDocument;
$doc->loadHTML(file_get_contents('testhtml.html'));
$container = $doc->createElement('div');
// $node = $doc->getElementsByTagName('div')->item(0);
// echo $doc->saveHTML($node);

function importHTML(DOMDocument $doc,DOMNode $node,String $file){
    $doc1 = new DOMDocument;
    $doc1->loadHTML($file);
    $tmp = $doc->importNode($doc1->getElementsByTagName('html')->item(0),true);
    return $tmp;
    //$node->appendChild($tmp);
}

// importHTML($doc,$node,file_get_contents('testhtml2.html'));


// function processNode(DOMNode $domNode,DOMDocument $doc){
//     $tags = array(
//         't-test' => 'testhtml2.html',
//         't-help' => 'testhtml3.html'
//     );

//     foreach ($domNode->childNodes as $node){
//         foreach ($tags as $key => $value) {
//             if($node->nodeName == $key){
//                 $template = importHTML($doc,$node,file_get_contents($value));
//                 $container = $doc->createElement('div');
//                 $container->appendChild($template);
//                 replaceNode($node,$container);
//             }
//         }
//         if($node->hasChildNodes()) {
//             processNode($node,$doc);
//         }
//     }
// }

// function processNodes(DOMDocument $doc){
//     $tags = array(
//         't-test' => 'testhtml2.html',
//         't-help' => 'testhtml3.html'
//     );

//     foreach ($tags as $key => $value) {
//         $nodes = $doc->getElementsByTagName($key);
//         while(count($nodes) > 0) { 
//             $template = importHTML($doc,$nodes[0],file_get_contents($value));
//             $container = $doc->createElement('div');
//             $container->appendChild($template);
//             replaceNode($nodes[0],$container);
//         }
//     }
// }

// function processNodes(DOMDocument $doc){
//     $tags = array(
//         't-test' => 'testhtml2.html',
//         't-help' => 'testhtml3.html'
//     );

//     foreach ($tags as $key => $value) {
//         $nodes = $doc->getElementsByTagName($key);
//         while(count($nodes) > 0) { 
//             $template = importHTML($doc,$nodes[0],file_get_contents($value));
//             $container = $doc->createElement('div');
//             $container->appendChild($template);
//             replaceNode($nodes[0],$container);
//         }
//     }
// }


class TagProcessor{
    private $doc;
    private $tags = [];

    function __construct(DOMDOcument $doc){
        $this->doc = $doc;
    }

    // public registerTag(String $name,String $template){
    // }
    
}


function processTags(DOMDocument $doc){
    $tags = array(
        't-test' => 'testTag',
        't-help' => 'helpTag',
        't-for' => 'forTag'
    );

    foreach ($tags as $key => $value) {
        $nodes = $doc->getElementsByTagName($key);
        while(count($nodes) > 0) {
            //$testTAG = new htmlTag($doc,$nodes[0],'test.php');
            $testTAG = new $value($doc,$nodes[0]);
            $testTAG->renderTag();
            $nodes = $doc->getElementsByTagName($key);
        }
    }
}


// processNodes($doc);
// echo $doc->saveHTML();


class htmlTag{
    private $doc;
    private $node;
    protected $name = NULL;
    private $model = [];
    private $tagNode;

    /* protected */public function setUpTag(DOMDocument $doc,DOMNode $node){
        $this->doc = $doc;
        $this->node = $node;    
    }

    protected function getAttributes(){
        $attributes = $this->node->attributes;
        $count = $attributes->length;
        for ($i=0; $i < $count; $i++) {
            if(!$attributes->item(i)->nodeName == 'static'){
                $this->model[$attributes->nodeName] = $attributes->nodeValue;
            }
        }
    }

    public function getTagNode(){
        return $this->tagNode;
    }

    protected function addToModel($model){
        array_merge($model,$this->model = $model);
    }

    public function renderTag(){
        $tagModel = new template\PageModel();
        $tagModel->templateFile = '/templates/tags/'.'_'.$this->name.".php";
        $tagModel->model = $this->model;
        $tagModel->body = $this->getInnerHTML($this->node);
        $this->tagNode = importHTML($this->doc,$this->node, $tagModel->setUpTemplate());
        $this->node->parentNode->replaceChild($this->tagNode, $this->node);
    }

    private function getInnerHTML(DOMNode $node){
        $res = '';
        if($node->hasChildNodes()){
            foreach ($node->childNodes as $value) {
                $res .= $this->doc->saveHTML($value);
            }
        }
        return $res;
    }
}

class testTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node){
        $this->setUpTag($doc,$node);
        $this->name = "test";
        $model = array(
            'test' => 'TEST'
        );
        $this->addToModel($model);
    }
}

class helpTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node){
        $this->setUpTag($doc,$node);
        $this->name = "help";
        $model = array(
            'test' => 'HELP'
        );
        $this->addToModel($model);
    }
}


class forTag extends htmlTag{
    function __construct(DOMDOcument $doc,DOMNode $node){
        $this->setUpTag($doc,$node);
        $this->name = "for";
        // $model = array(
        //     'test' => 'HELP'
        // );
        // $this->addToModel($model);
    }
}

// $node = $doc->getElementsByTagName('t-test')->item(0);
// $testTAG = new testTag($doc,$node);
// $testTAG->renderTag();
// processTags($doc);
// echo $doc->saveHTML();



function loopDOMNode(DOMDocument $doc,$iter) {
    $tags = array(
        't-test' => 'testTag',
        't-help' => 'helpTag',
        't-for' => 'forTag'
    );
    if($iter >= 256){
        return;
    }
    $found = 0;
    foreach ($tags as $key => $value) {
        $nodes = $doc->getElementsByTagName($key);
        if(count($nodes) > 0){
            $testTAG = new $value($doc,$nodes[0]);
            $testTAG->renderTag();
            $found += 1;
        }
    }
    if($found > 0){
        loopDOMNode($doc,$iter+1);
    }
}


// function showDOMNode(DOMNode $domNode,DOMDocument $doc) {
//     $tags = array(
//         't-test' => 'testTag',
//         't-help' => 'helpTag'
//     );
//     foreach ($tags as $key => $value) {
//         if($domNode->nodeName == $key){
//             $testTAG = new $value($doc,$domNode);
//             $testTAG->renderTag();
//             showDOMNode($testTAG->getTagNode(),$doc);
//         }
//     }

//     foreach ($domNode->childNodes as $node)
//     {
//         foreach ($tags as $key => $value) {
//             if($domNode->nodeName == $key){
//                 $testTAG = new $value($doc,$node);
//                 $testTAG->renderTag();
//                 showDOMNode($testTAG->getTagNode(),$doc);
//             }
//         }
//         if($node->hasChildNodes()) {
//             showDOMNode($node,$doc);
//         }
//     }    
// }

loopDOMNode($doc,0);
// showDOMNode($doc,$doc);
echo $doc->saveHTML();

libxml_use_internal_errors($libxmlErrors);
?>