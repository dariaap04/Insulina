<?php
       $localhost= "localhost";
       $username='root';
       $pw="";  
       $database = "diabetes_db"; 

       $con = new mysqli ($localhost, $username, $pw, $database);
      /*  if($con->connect_error) {
           die("Connection failed: ". $con->connect_error);
       }else{
        die("Connection succeeded");
       } */
?>