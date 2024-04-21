<?php
    include "db_conn.php";
    $id = $_GET['id'];
    $sql = "DELETE FROM `products` WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("Location: index.php?msg=Product Delete Successfully");
    }else{
        echo "Failed: ".mysqli_error($conn); 
    }
?>