<html>
<body>
		
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
 <input type="file" name="user_image">
 <input type="submit" name="btnsave" value="Upload">
</form>

</body>
</html>



<?php
 $DB_HOST = 'localhost';
 $DB_USER = 'root';
 $DB_PASS = '';
 $DB_NAME = 'images';
 
 try{
  $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
  $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }
 catch(PDOException $e){
  echo $e->getMessage();
}
 
 if(isset($_POST['btnsave']))
 {

  $imgFile = $_FILES['user_image']['name'];
  $tmp_dir = $_FILES['user_image']['tmp_name'];
  $imgSize = $_FILES['user_image']['size'];
  
  
  if(empty($imgFile)){
   $errMSG = "Please Select Image File.";
  }
  else
  {
   $upload_dir = 'images/'; // upload directory
 
   $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
  
   // valid image extensions
   $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
  
   // rename uploading image
   $userpic = $imgFile.".".$imgExt;
    
   // allow valid image file formats
   if(in_array($imgExt, $valid_extensions)){   
    // Check file size '5MB'
    if($imgSize < 5000000)    {
     move_uploaded_file($tmp_dir,$upload_dir.$userpic);
    }
    else{
     $errMSG = "Sorry, your file is too large.";
    }
   }
   else{
    $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";  
   }
  }
  
  
  // if no error occured, continue ....
  if(!isset($errMSG))
  {
   $stmt = $DB_con->prepare('INSERT INTO image() VALUES(:upic)');
   $stmt->bindParam(':upic',$userpic);
   
   if($stmt->execute())
   {
    echo $successMSG = "new record succesfully inserted ...";
   }
   else
   {
    echo $errMSG = "error while inserting....";
   }
  }
 }
?>