<?php
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: *');


    require 'config.php';
    require 'vendor/autoload.php';
    $dotenv=Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    include_once 'createJwt.php';
    include_once 'checkjwt.php';
    class Todo extends Config{
        public function __construct(){
            parent::__construct();
        }
public function allproducts(){
    $query="SELECT * from products where status='active'";
    $result=parent::fetch_all($query);
    
    $getImages="SELECT * from images";
    $results=parent::fetch_all($getImages);
    return json_encode(["products"=>$result,"images"=>$results]);
}
public function allcate(){
  $query="SELECT * from categories";
  $result=parent::fetch_all($query);
  return $result;
}
public function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

public function addcate($data){
  $catName=$this->test_input($data['Category_name']);
  $query="INSERT into categories (Category_name)  VALUE (?) ";
  $binder = array('s',$catName);
  $result = parent::Create($query,$binder);


}       
public function regcustomer($data){
  $fname=$this->test_input($data['fname']);
            $lname=$this->test_input($data['lname']);            
            $email=$this->test_input($data['email']);
            $phone=$this->test_input($data['phone']);
            $realpass=$this->test_input($data['password']);
            $password=password_hash( $realpass,PASSWORD_DEFAULT);
            $query=" INSERT into customer (FirstName,LastName,email,telephone,password,realpass)values(?,?,?,?,?,?)";
            $binder = ['ssssss',$fname,$lname,$email,$phone,$password,$realpass];
            $result = parent::Create($query,$binder);
            $check=json_decode($result,true);
            if($check['lastId']==0){
              echo json_encode(['status'=>false,'message'=>'Operation Failed . E-mail already exist']);
            }
            else{ 
              echo $result;
            }
            
}
         public function productupload($data){
            $name=$this->test_input($data['productName']);
            $discount=$this->test_input($data['discount']);            
            $price=$this->test_input($data['price']);
            $CategoryId=$this->test_input($data['categoryId']);
            $fileNumber=$data['fileNumber'];
            $minimum=$this->test_input($data['minimum']);
            
            $about=$this->test_input($data['about']);
            $query="INSERT into products (productName,price,discount,category_id,fileNumbers,minimum,about)  VALUES (?,?,?,?,?,?,?) ";
            $binder = ['sssssss',$name,$price,$discount,$CategoryId,$fileNumber,$minimum,$about];
            $result = parent::Create($query,$binder);
            return $result;
            }


          public function uploadimages($files,$result){    
            $thisFiles= $files['name'];
$tmp= $files['tmp_name'];
$size=$files['size'];
for ($i=0; $i <count($tmp) ; $i++) { 
    
   $fileName=$thisFiles[$i];
       $valid_extensions=array('jpg','jpeg','png','pdf');
     $extension=pathinfo($fileName,PATHINFO_EXTENSION);
     if(in_array(strtolower($extension),$valid_extensions)){
        if($size[$i]<200000){
         $returnvalue=null;
        }
        else{
         return json_encode(['status'=>false,'message'=>'Operation failed. File size must not exceed 20,000kb']);
            }
         }
            else{
             return json_encode(['status'=>false,'message'=>'Operation failed. Wrong file type']);  
          }        
        
        }
         
        for ($i=0; $i <count($tmp); $i++) { 
    $thisFiles= $files['name'];
    $tmp= $files['tmp_name'];
   $fileName=$thisFiles[$i];
   $lastId=$result['lastId'];
//    return json_encode($lastId['lastId']);
   if(strlen($fileName)>100){
$fileName=substr($fileName,0,100);
   }
    $moveds=move_uploaded_file($tmp[$i],"../frontend/src/assets/uploads/". $fileName);
              $query=" INSERT into images (imageName,product_id)values(?,?)";
              $binder = ['si',$fileName,$lastId];
              $results = parent::Create($query,$binder);

        if($i==count($tmp)-1){
            return $results;
        }
    
}
          }
          public function removelast($data){

            $lastId=$this->test_input($data['lastId']);
            $query="DELETE FROM products
            WHERE product_id='$lastId' ";
             
            $results = parent::execute($query);
          }
          public function getProduct($data) {
            $productId = trim($data['productId']);            
    $query="SELECT * FROM products JOIN images USING (product_id) WHERE products.product_id='$productId' ";
        $result = parent::fetch_one($query);
        return $result;

           
          }
          public function editupload($data){
            $name=$this->test_input($data['productName']);
            $discount=$this->test_input($data['discount']);            
            $price=$this->test_input($data['price']);
            $CategoryId=$this->test_input($data['categoryId']);
            $fileNumber=$data['fileNumber'];
            $minimum=$this->test_input($data['minimum']);            
            $about=$this->test_input($data['about']);
            $productId=$data['product_id'];
            $query="UPDATE products SET productName=?,price=?,discount=?,category_id=?,fileNumbers=?,minimum=?,about=? WHERE product_id='$productId'";
            $binder = ['sssssss',$name,$price,$discount,$CategoryId,$fileNumber,$minimum,$about];
            $result = parent::Create($query,$binder);
            $result=json_decode($result,true);
                  if($result['status']==true){
                  $result=['status'=>true,'message'=>'successfully Updated','lastId'=>$productId];
                  }
            return json_encode( $result);
            }

public function cusignin($data){
  $email=$this->test_input($data['email']);
  $password=$this->test_input($data['password']);
  $query="SELECT * from customer where email='$email' ";
  $result=parent::signins($query,$password);
  $datas=json_decode($result,true);

  $genJwt=new GenerateJwt;
  $token=$genJwt->genJwt($datas['data']);
  $datas['token']=$token;
  $datas['cartId']=$datas['data']['cart_id'];
  echo json_encode($datas);
}
// public function addTodo($data){

            
//     $todo = $data->todo;
    // $price = $data->price;

    // echo json_encode($data->item);
//     $query = "INSERT INTO todos (task) VALUE (?)";
//     $binder = array('s',$todo);
//     $check = parent::Create($query, $binder);

//     return $todo;
// }
public function newcart($data){
  $allheaders=getallheaders();
$val=$allheaders['Authorization'];
$myJwt=trim(substr($val,7));
  $check=\Firebase\JWT\JWT::decode($myJwt,$_ENV['SECRET'],['HS256']);
$customer_id=$check->info->userId;
// return json_encode($check);
  // return json_encode($checks);
  $binder = ['s',$customer_id];
  if($data['lastId']){
  $cartitem=$this->cartitem($data);
  return $cartitem;
  }
  $query="INSERT into carts (customer_id)  VALUES (?) ";
  $inserted = parent::Create($query,$binder);
  
  $result=json_decode($inserted,true);
  $binder = ['s',$result['lastId']];
$data['lastId']=$result['lastId'];
  $query="UPDATE customer set cart_id=? where customer_id='$customer_id'";
  $result = parent::Create($query,$binder);

  $this->cartitem($data);
  return json_encode( $data);
  }
  // INSERT INTO cartitems (cart_id,item_name,discount,price,product_id) VALUES (47,'egg',20,20,19)
public function cartitem($data){
  // return json_encode( $data);
$query="INSERT into cartitems (cart_id,item_name,discount,price,product_id) values (?,?,?,?,?)";
$cartId=$data['lastId'];
$name=$data['productName'];
$id=$data['productId'];
$price=$data['productPrice'];
$discount=$data['discount'];

$binder = ['sssss',$cartId,$name,$discount,$price,$id];

$result = parent::Create($query,$binder);
$query="SELECT * from cartitems where cart_id='$cartId'";

$cartitems=parent::fetch_all($query);
$cartitems=json_decode($cartitems,true);
for ($i=0; $i <count($cartitems); $i++) { 
  $itemId=$cartitems[$i]['product_id'];
  if($itemId==$id){
    return json_encode(['status'=>false,'message'=>'Item already added']);

  }
}
$result=json_decode($result,true);
$result['cartitems']=$cartitems;
return json_encode( $result);
}
public function fetchcart($data){
  $myJwt=$data['token'];
  // return json_encode($data);
  $check=\Firebase\JWT\JWT::decode($myJwt,$_ENV['SECRET'],['HS256']);
  $customer_id=$check->info->userId;
  $query="SELECT cart_id from customer where customer_id='$customer_id' ";
  $result = parent::fetch_one($query);
  $result=json_decode($result);
$id=$result->cart_id;
  $query="SELECT * from cartitems where cart_id='$id' ";
  $result=parent::fetch_all($query);
  return $result;


}







    }
?>