<?php
session_start();
ini_set('display_errors',1);
	echo $_REQUEST['content'];
	
$_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
$_SESSION['KCFINDER']['uploadURL'] = "../uploads/"; // URL for the uploads folder
$_SESSION['KCFINDER']['uploadDir'] = "../uploads/"; // path to the uploads 

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

</head>

<body id="editable_content">
<p>textarea:</p>
<p>&nbsp;</p>
<p>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
  <p>
  

    <textarea  name="content"  id="editor1" > </textarea>
    
    
<script type="text/javascript">
    CKEDITOR.replace( 'editor1', {
         filebrowserBrowseUrl: 'kcfinder/browse.php?type=files',
         filebrowserImageBrowseUrl: 'kcfinder/browse.php?type=images',
         filebrowserFlashBrowseUrl: 'kcfinder/browse.php?type=flash',
         filebrowserUploadUrl: 'kcfinder/upload.php?type=files',
         filebrowserImageUploadUrl: 'kcfinder/upload.php?type=images',
         filebrowserFlashUploadUrl: 'kcfinder/upload.php?type=flash'
    });
	

</script>
 
<div contenteditable="true">
    Editable text
</div>    
  </p>
  <p>
    <input type="submit" name="button" id="button" value="Submit">
  </p>
</form>
</p>
</body>
</html>
