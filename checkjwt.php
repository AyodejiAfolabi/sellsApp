

<?php
 require 'vendor/autoload.php';
 $dotenv=Dotenv\Dotenv::createImmutable(__DIR__);
 
 $dotenv->load();
 use \Firebase\JWT\JWT;
class CheckJwt {
   
    public function decode($data){

        $jwt=JWT::decode($data,$_ENV['SECRET'],array('HS256'));
// return $jwt;
    }



    }

?>