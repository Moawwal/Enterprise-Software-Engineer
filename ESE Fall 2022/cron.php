<?php  
function save_new_file($dblink, $uploaded_by, $file_name, $loan_number, $category, $content, $path ){
    //split in an array and add into variable
	$date_created = date("Y-m-d H:i:s");
    $errorCode = 0;
    $sql="INSERT INTO documents(auto_id, name, loan_number, category, upload_by, upload_date, content, path) VALUES (NULL,'$file_name','$loan_number','$category','$uploaded_by','$date_created','$content','$path')";
    } 
?>