<?php

header('Access-Control-Allow-Headers:Authorization');

   require_once 'Todo.php';
   $sa = new Todo;
   if(isset($_POST)){

      if($_POST['activity']=='addcate'){
       $result=$sa->addcate($_POST);
echo ($result);
      }
     
      if($_POST['activity']=='getProduct'){
         $result=$sa->getProduct($_POST);
  echo ($result);
        }
        if($_POST['activity']=='regcustomer'){
         $result=$sa->regcustomer($_POST);
  echo ($result);
        }
       
        if($_POST['activity']=='cusignin'){
         $result=$sa->cusignin($_POST);
  echo ($result);
        }


      if($_POST['activity']=='productupload'){
         $result=$sa->productupload($_POST);
         $result=json_decode($result,true);
         if($result['status']==true){
         $uploadimages= $sa->uploadimages($_FILES['file'],$result);
         $upload=json_decode($uploadimages,true);
         if($upload['status']==true){
        echo $uploadimages;
         }
         else{            
         $removelast= $sa->removelast($result);         
        echo $uploadimages;
         }
         }
         else{
               echo $result;
         }
// echo $uploadimages;
//   echo json_encode($_FILES['file']);
        }





        if($_POST['activity']=='editupload'){
    $result=$sa->editupload($_POST);
    $display=$result;
         $result=json_decode($result,true);
         if($result['status']==true){
            if($_FILES){
         $uploadimages= $sa->uploadimages($_FILES['file'],$result);
         $upload=json_decode($uploadimages,true);
         if($upload['status']==true){
        echo $display;
         }
         else{                    
        echo json_encode(['status'=>false,'message'=>"Update Succesfully. You can't upload a file with a size of more than 20,000kb "]);
         }
         }
         else{
            echo $display;
            
         }
      }
         else{
               echo $display;
         }
      }


      if($_POST['activity']=='newcart'){
         $result=$sa->newcart($_POST);
  echo ($result);
        }
        if($_POST['activity']=='cartitem'){
         $result=$sa->cartitem($_POST);
  echo ($result);
        }
        if($_POST['activity']=='fetchcart'){
         $result=$sa->fetchcart($_POST);
  echo ($result);
        }
   }
  
?>