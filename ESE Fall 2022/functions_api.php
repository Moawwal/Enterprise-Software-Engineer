<?php
//include("api_create_query_receive.php"); 
function db_connect($db)
{
	$hostname="localhost";
    $username="webuser";
    $password="CHg_OIfuD[7u[SNR";
	//$username="root";
	//$password="testing123";
    $db="docstorage";
    $dblink=new mysqli($hostname,$username,$password,$db);
	if (mysqli_connect_errno())
    {
        die("Error connecting to database: ".mysqli_connect_error());   
    }
	return $dblink;
}

function save_file($dblink, $uploaded_by, $file_name, $upload_date, $status, $file_type, $content, $path )
{
	
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    date_default_timezone_set("America/Chicago");
    $date_created = date("Y-m-d H:i:s");
	$sql="INSERT INTO documents(`auto_id`, `name`, `path`, `upload_by`, `upload_date`, `file_type `content`) VALUES (NULL,'$file_name',$uploaded_by','$date_created','$file_type' '$content','$path')";
}

function redirect ( $uri )
{ ?>
	<script type="text/javascript">
	<!--
	document.location.href="<?php echo $uri; ?>";
	-->
	</script>
<?php die;
}
?>
