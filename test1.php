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

function processNodes(DOMDocument $doc){
    $tags = array(
        't-test' => 'testhtml2.html',
        't-help' => 'testhtml3.html'
    );

    foreach ($tags as $key => $value) {
        $nodes = $doc->getElementsByTagName($key);
        while(count($nodes) > 0) { 
            // $template = importHTML($doc,$nodes[0],file_get_contents($value));
            // $container = $doc->createElement('div');
            // $container->appendChild($template);
            // replaceNode($nodes[0],$container);
            $testTAG = new htmlTag($doc,$nodes[0],'test.php');
            $testTAG->renderTag();
            $nodes = $doc->getElementsByTagName($key);
        }
    }
}

processNodes($doc);
echo $doc->saveHTML();


class htmlTag{
    private $doc;
    private $template;
    private $node;

    function __construct(DOMDocument $doc,DOMNode $node,String $template){
        $this->doc = $doc;
        $this->template = $template;
        $this->node = $node;
    }

    function renderTag(){
        $tagModel = new template\PageModel();
        $tagModel->templateFile = '/templates/layouts/'. $this->template;
        $tagModel->model = array(
            'test' => "HELOOOOO"
        );
        $tagModel->body = $this->getInnerHTML($this->node);
        $renderedTag = importHTML($this->doc,$this->node, $tagModel->setUpTemplate());
        $this->node->parentNode->replaceChild($renderedTag, $this->node);
    }

    private function getInnerHTML(DOMNode $node){
        $res = '';
        foreach ($node->childNodes as $value) {
            $res .= $this->doc->saveHTML($value);
        }
        return $res;
    }
}

// $node = $doc->getElementsByTagName('t-test')->item(0);
// $testTAG = new htmlTag($doc,$node,'test.php');
// $testTAG->renderTag();

// echo $doc->saveHTML();

libxml_use_internal_errors($libxmlErrors);
?>