<?php

    header("Access-Control-Allow-Origin:http://localhost:4200");
    // header("Access-Control-Allow-Headers: Content-Type");
    header('Access-Control-Allow-Headers:*');
    header("Access-Control-Allow-Credentials: true");
    define("host", "localhost");
    define("username", "root");
    define("password","");
    define("dbName", "gracefarm");
    include_once 'createJwt.php';

    class Config {

        public function __construct(){
            $this->connect();
        }

        public function connect(){
            $this->con = new mysqli(host,username,password,dbName);
            if($this->con->connect_error){
                die('unable to connect'. $con->connect_error);
            }
        }

        public function Create($query,$binder){
            $stmt = $this->con->prepare($query);
            $stmt->bind_param(...$binder);
            $stmt->execute();
        //   echo json_encode ($stmt);
            if($stmt){
                $last_id = $this->con->insert_id
                ;
                return json_encode(['status'=>true,'message'=>'successfully added','lastId'=>$last_id]);
            }
            else{
                return json_encode(['status'=>false,'message'=>'Operation failed']);
            }
    
        }
     

        public function fetch_all($query){
            $result = $this->con->query($query);
          $result=$result->fetch_all(MYSQLI_ASSOC);
return json_encode($result);
        }

        public function fetch_one($query){
            $result = $this->con->query($query);
          $result=$result->fetch_assoc();
return json_encode($result);
        }
        public function execute($query){
            $result = $this->con->query($query);
                 }

        public function testing($query){
           $result= $this->con->query($query);
           if($result){
           return json_encode('true');
           }
           else{
            return json_encode('false'); 
           }
        }


        public function signins($query,$password){
         
            $result=$this->con->query($query);
            if($result){
                $fetched=$result->fetch_assoc();
                $verify=password_verify($password,$fetched['password']);
                if($verify){
                    return json_encode(['status'=>true,'message'=>'Login Successful','data'=>$fetched]);
                }
                else{
                    return json_encode(['status'=>false,'message'=>'Incorrect Password']);
                }
            }
            else{
                return json_encode(['status'=>false,'message'=>'Incorrect Email']);
            }

            
        }
    }




    

?>