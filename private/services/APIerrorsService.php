<?php

class APIerrors {

    public function json($data){
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function error($code,String $message){
        return ['error'=>$code,'message'=> $message];
    }

    public function success(){
        return $this->error(0,'ok');
    }

    public function notauth(){
        return $this->error(401,L::error_notauth);
    }

    public function notfound(){
        return $this->error(404,L::error_notfound);
    }

    public function servererror(){
        return $this->error(500,L::error_general);
    }
}


?>
