<?php

    require_once 'Todo.php';
    // header('Access-Control-Allow-Headers:Authorization');

    // echo json_encode($_REQUEST);
    $sa = new Todo;
    $result = $sa->allcate();
  
    echo $result;
?>