<?php
if(!defined('ROOT')){
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
}
define('FORM_METHOD_POST', 0);
/*
/   DATABASE CONNECTION CLASS
/   ----------------------------------------
/   questa classe si occupa di inizializzare il database e mantenere una connessione 
/   prima di una transazione bisogna aprire la connessione connect()
/   e chiuderla con close()
/   questa classe e un sigleton perche viene chiamata molte volte ma non e necessario che sia
/   instanziata ogni volta riducendo cosi il carico sul server e anche perche si occupa di caricare
/   le classi di dominio (che sono inheritance di DOMAIN) che mappano le tabelle del database in classi
*/
class dbConnection {
    private $servername = "localhost";
    private $username = "luca";
    private $password = "15421542dD";
    private $databaseName = "taskmgr";
    private $db;
    private static $instance = null;

    public static function getInstance(){
      if(self::$instance == null){   
         $c = __CLASS__;
         self::$instance = new $c;
      }
      
      return self::$instance;
    }

    private function __construct(){
        $this->loadDomainClasses();
        $this->connect();
    }

    function __destruct(){
        $this->close();
    }

    private function loadDomainClasses(){
        foreach (scandir(ROOT.'/private/domains') as $filename) {
            $path = ROOT.'/private/domains/'.$filename;
            if (is_file($path)) {
                require_once($path);
            }
        }
    }

    public function connect(){
        $this->db = new \mysqli($this->servername, $this->username, $this->password,$this->databaseName);
        if ($this->db->connect_error) {
            throw new \Exception("Cannot connect to database: ". $this->db->connect_error);
        }
    }

    public function close(){
        $this->db->close();
    }

    function __call(string $name ,array $arguments ){
        if($this->db){
            return $this->db->$name(...$arguments);
        }else{
            $this->$name(...$arguments);
        }
    }

    function __get(string $name){
        if($this->db){
            return $this->db->$name;
        }else{
            return $this->$name;
        }
    }
}


/*
/   DOMAIN ABSTRACT CLASS
/   ----------------------------------------
/   Questa e la classe base di tutte le classi di Dominio si occupa di Mappare le tabelle
/   del database in classi piu managgevoli da usare su php
/   utilizzando questo tipologia di mappatura dove bisogna specificare per ogni classe le
/   connessioni con altre classi (implementando i metodi astratti benlongsTo e hasMany)
/   quindi quando si esegue una query con la funzione findAll (es: SELECT * FROM Orders) vengono
/   ritornate tutte le istanze della tabella Orders mappate sulla classe di Dominio Orders nel caso
/   la tabella Orders ha una foreign key in una sua colonna questa proprieta viene mappata nella classe
/   non come valore dell'id della foreign key ma con una istanza della classe di dominio alla quale quella tabella
/   e mappata (es: Orders FK PersonID -> (PersonID,'Persons') viene ritornata una istanza della classe Persons)
/   con questa tipologia di implementazione e necessario evitare tabelle cicliche
*/
abstract class Domain{

    function __construct(...$params){
        foreach ($params as $value) {
            $this->{$value[0]} = $value[1];
        }
        $this->mapToClass($params);
    }

    abstract public function belongsTo();
    abstract public function hasMany();
    abstract public function primaryKey();

    public static function fromData($params){
        if(!array_key_exists('domain',$params))
            return false;
        $reflection = new ReflectionClass($params['domain']);
        $proprieties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $args = [];
        foreach ($proprieties as $key => $value) {
            $prop = $value->name;
            if(isset($params[$prop])){
                array_push($args,array($prop,$params[$prop]));
            }
        }
        return new $params['domain'](...$args);
    }

    public static function findAll(String $query,$variables=NULL,$limits=NULL){
        $query = preg_replace("/@this/",get_called_class(),$query);

        if($variables){
            foreach ($variables as $key => $value) {
                $query = preg_replace("/:".$key."/","'".$value."'",$query);
                $query = preg_replace("/@".$key."/",$value,$query);

            }
        }

        if(!$query) 
            throw new Exception('Errors in query decompilation!!');

        if($limits){
            if(array_key_exists('max',$limits))
                $query .= " LIMIT ".$limits['max'];
            if(array_key_exists('offset',$limits))
                $query .= ' OFFSET '.$limits['offset'];
        }
        $database = dbConnection::getInstance();
        if(!$result = $database->query($query))
            throw new \Exception("Error executing query (".$query."): ". $database->error);
        $resultAssoc = $result->fetch_all(MYSQLI_ASSOC);

        $array = [];
        foreach ($resultAssoc as $id => $value) {
            $params = [];
            $i = 0;
            foreach ($value as $key => $item) {
                $params[$i] = array($key,$item);
                $i++;
            }
            $c = get_called_class();
            $array[$id] = new $c(...$params);
        }
        return $array;
    }

    public static function find(String $query,$variables=NULL){
        $class = get_called_class();
        if($result = $class::findAll($query,$variables,['max'=>1])){
            return $result[0];
        }else{
            return false;
        }

    }

    public function save(){
        $class = get_called_class();
        $pkey = $this->primaryKey();
        $isDB = $class::findAll("SELECT * FROM @this WHERE @primary=:value",
            ['primary'=>$pkey,'value'=>$this->{$pkey}]);
        $database = dbConnection::getInstance();
        $class = get_called_class();
        if($isDB){
            $query = "SHOW COLUMNS FROM ".$class;
            $result = $database->query($query);
            $resultAssoc = $result->fetch_all(MYSQLI_ASSOC);
            if(!$resultAssoc){
                throw new \Exception("Error executing query (".$query."): ". $database->error);
            }
            $query = "UPDATE ".$class." SET ";
            foreach ($resultAssoc as $key => $value) {
                if(isset($this->belongsTo()[$value['Field']]))
                    $val = $this->{$value['Field']}->id;
                else
                    $val = $this->{$value['Field']};
                $query .= $value['Field']."=".(($val != null)? "'".$val."'" : "null")."";
                if(($key+1) < count($resultAssoc))
                    $query .= ",";
            }
            $query .= " WHERE ".$this->primaryKey()."='".$this->{$this->primaryKey()}."'";
        }else{
            $query = "SHOW COLUMNS FROM ".$class;
            $result = $database->query($query);
            $resultAssoc = $result->fetch_all(MYSQLI_ASSOC);
            if(!$resultAssoc){
                throw new \Exception("Error executing query (".$query."): ". $database->error);
            }
            $query = "INSERT INTO ".$class." VALUES( ";
            foreach ($resultAssoc as $key => $value) {
                if($this->{$value['Field']} != null ){
                    if(isset($this->belongsTo()[$value['Field']]))
                        $query .= "'".$this->{$value['Field']}->id."'";
                    else
                        $query .= "'".$this->{$value['Field']}."'";
                }else{
                    $query .= 'null';
                }
                if(($key+1) < count($resultAssoc))
                    $query .= ",";
            }
            $query .= ")";
        }
        $result = $database->query($query);
        if(!$result){
            throw new \Exception("Error executing query (".$query."): ". $database->error);
        }else{
            if($isDB)
                return true;
            else { 
                $query = "SELECT * FROM ".$class." WHERE ".$this->primaryKey()."='".$database->insert_id."'";
                if(!$result = $database->query($query)){
                    throw new \Exception("Error executing query (".$query."): ". $database->error);
                }
                $resultAssoc = $result->fetch_all(MYSQLI_ASSOC);
                if($resultAssoc)
                    $resultAssoc = $resultAssoc[0];
                else 
                    return false;
                foreach ($resultAssoc as $key => $value) {
                    $this->$key = $value; 
                }
                return true;
            }
        }
        return false;

    }

    private function mapToClass($result){
        if($this->belongsTo()){
            foreach ($this->belongsTo() as $key => $value) {
                $val = $this->getKeyOfResult($result,$key);
                $class = new $value();
                $this->$key = $value::find("SELECT * FROM @table WHERE @primary=:value",
                    ['table'=>$value,'primary'=>$class->primaryKey(),'value'=>$val]);
                unset($class);
            }
        }

        if($this->hasMany()){
            foreach ($this->hasMany() as $key => $value) {
                $val = $this->getKeyOfResult($result,$key);
                $class = new $value();
                $this->$key = $value::findAll("SELECT * FROM @table WHERE @primary=:value",
                    ['table'=>$value,'primary'=>$class->primaryKey(),'value'=>$val]);
                unset($class);   
            }
        }
    }

    private function getKeyOfResult($result,$key){
        $val = '';
        for ($i=0; $i < count($result); $i++) { 
            if($result[$i][0] == $key) {
                $val = $result[$i][1];
                break;
            }
        }
        return $val;
    }
}

dbConnection::getInstance();

?>