<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script>
        function add_alert(){
            alert('Added Sucessfully');
        }
    </script>
</body>
</html><?php
  require("connection.php");
  function image_upload($img){  /* this function will store image in it*/
    $tmp_loc = $img['tmp_name']; /* Get the temporary location of the uploaded file */
    $new_name = random_int(11111,99999).$img['name']; /* this will provide random unique number to same name image*/
    $new_loc = UPLOAD_SRC.$new_name;

    if(!move_uploaded_file($tmp_loc,$new_loc)){
        header("location: index.php?alert=img_upload");
        exit;
    }
    else{
        return $new_name;
    }
  }

  function image_remove($img){     /* for line 42 */
    if(!unlink(UPLOAD_SRC.$img)){    /* unlink deletes the file */
        header("location: index.php?alert=img_rem_fail");   // it get us back to the the index.php
        exit;
    }
  }

  if(isset($_POST['addproduct']))
  {
    foreach($_POST as $key => $value){
        $_POST[$key] = mysqli_real_escape_string($con,$value);/* if value will be filtered and restore on same location if any harnful data is present that detroy our database will be removed*/
    }
    $imgpath = image_upload($_FILES['image']);
    $query = "INSERT INTO `products`(`name`, `price`, `description`, `image`) 
            VALUES ('$_POST[name]','$_POST[price]','$_POST[desc]','$imgpath')";
            if(mysqli_query($con,$query)){
                header("location: index.php?success=add");/* If the query is successful (mysqli_query returns true), this line sends an HTTP header to the browser to redirect the user to index.php with a query parameter success=add.
                The Location header is used to redirect the browser to a different URL.
                index.php?success=add indicates that the operation was successful, and this information can be used to display a success message on the index.php page. */
            }
            else{
                header("location: index.php?alert=add_failed");
            }
  }

  if(isset($_GET['rem']) && $_GET['rem'] > 0)
  {
    $query = "SELECT * FROM `products` WHERE `id`='$_GET[rem]'";
    $result = mysqli_query($con,$query);
    $fetch = mysqli_fetch_assoc($result);

    image_remove($fetch['image']);     /* line 25 */

    $query = "DELETE FROM `products` WHERE `id`='$_GET[rem]'";
    if(mysqli_query($con,$query)){
        header("location: index.php?success=removed");
    }
    else{
        header("location: index.php?alert=removed_failed");
    }
  }

  if(isset($_POST['editproduct']))  // this code run when user edit the content
  {
    foreach($_POST as $key => $value){
        $_POST[$key] = mysqli_real_escape_string($con,$value); //detail filter special symbol ignore
    }
    if(file_exists($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['image']['tmp_name'])){
        $query = "SELECT * FROM `products` WHERE `id`='$_POST[editpid]'";
        $result = mysqli_query($con,$query);
        $fetch = mysqli_fetch_assoc($result);

        image_remove($fetch['image']);

        $imgpath = image_upload($_FILES['image']);

        $update = "UPDATE `products` SET `name`='$_POST[name]',`price`='$_POST[price]',`description`='$_POST[desc]',`image`='$imgpath' WHERE `id`='$_POST[editpid]'";
    }
    else{
        $update = "UPDATE `products` SET `name`='$_POST[name]',`price`='$_POST[price]',`description`='$_POST[desc]',`image`='$imgpath' WHERE `id`='$_POST[editpid]'";
    }
    if($update = mysqli_query($con,$update)){
        header("location: index.php?success=removed");
    }
    else{
        header("location: index.php?alert=update_failed");
    }
  }
  
?>