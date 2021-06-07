<?php

    require_once 'Todo.php';

    // echo json_encode($_REQUEST);
    $sa = new Todo;
    $result = $sa->allproducts();
  
    echo $result;
?>