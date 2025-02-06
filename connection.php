<?php
  $con = mysqli_connect("localhost","root","","crud");
  if(mysqli_connect_errno()){
    die("Cannot Connect to Database".mysqli_connect_errno());
  }
    /* it shows current root path/address of server for uploading image with "/crud/uploads/"*/
  define("UPLOAD_SRC",$_SERVER['DOCUMENT_ROOT']."/crud/uploads/");

  define("FETCH_SRC","http://127.0.0.1/crud/uploads/");  /* to read our image from upload folder */
?>