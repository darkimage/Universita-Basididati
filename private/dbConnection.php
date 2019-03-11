<?php

class dbConnection {
    private $servername = "localhost";
    private $username = "luca";
    private $password = "15421542dD";
    private $databaseName = "taskmgr";
    private $db;
    private static $instance = null;

    public static function getInstance()
    {
      if(self::$instance == null)
      {   
         $c = __CLASS__;
         self::$instance = new $c;
      }
      
      return self::$instance;
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



abstract class Domain{

    function __construct(...$params){
        foreach ($params as $value) {
            $this->{$value[0]} = $value[1];
        }
        $this->mapToClass($params);
    }

    abstract protected function belongsTo();
    abstract protected function hasMany();

    public static function findAll(String $query,$variables=NULL,$limits=NULL){
        if($variables){
            foreach ($variables as $key => $value) {
                preg_replace(":".$key,$value,$query);
            }
        }
        if($limits){
            $query .= " LIMIT ".$limits['max'].' OFFSET '.$limits['offset'];
        }
        $database = dbConnection::getInstance();
        $database->connect();
        if(!$result = $database->query($query)){
            throw new \Exception("Error executing query (".$query."): ". $database->error);
        }

        $array = [];
        foreach ($result as $id => $value) {
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
        return $class::findAll($query,$variables,['max'=>1,'offset'=>0])[0];
    }

    private function mapToClass($result){
        if($this->belongsTo()){
            foreach ($this->belongsTo() as $key => $value) {
                $val = '';
                for ($i=0; $i < count($result); $i++) { 
                    if($result[$i][0] == $key) {
                        $val = $result[$i][1];
                        break;
                    }
                }
                $this->$key = $value::find("SELECT * FROM ".$value." WHERE id=".$val);
            }
        }
    }

}

class Persons extends Domain{
    public $id;
    public $LastName;
    public $FirstName;
    public $Address;
    public $City;

    protected function belongsTo(){}

    protected function hasMany(){}
}

class Orders extends Domain{
    public $id;
    public $OrderNumber;
    public $PersonID;

    protected function belongsTo(){
        return array('PersonID'=>'Persons');
    }

    protected function hasMany(){}
}

$test = Orders::findAll("SELECT * FROM Orders");

$t = 2;

?>