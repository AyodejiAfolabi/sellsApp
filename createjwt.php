<?php
 require 'vendor/autoload.php';
 $dotenv=Dotenv\Dotenv::createImmutable(__DIR__);
 
 $dotenv->load();
 use \Firebase\JWT\JWT;
class GenerateJwt {
   
    public function genJwt($data){
        
        
        $details=[
            "iss"=>'localhost:8080',
            'iat'=>time(),
            'nbf'=>time(),
            'exp'=>null,
            'info'=>[
                'username'=>$data['email'],
        'userId'=>$data['customer_id']
            ]
            ];
 
        $jwt=JWT::encode($details,$_ENV['SECRET']);
return $jwt;
    }



    }

?>