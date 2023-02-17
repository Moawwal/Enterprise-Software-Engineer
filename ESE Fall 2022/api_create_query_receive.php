<?php
//$dblink=db_connect("docstorage");
$username="kyo537";
$password='M9R7tgYrcmJ8pQC6';
$data="username=$username&password=$password";
$ch=curl_init('https://cs4743.professorvaladez.com/api/create_session');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER,array(
	'content-type: application/x-www-form-urlencoded',
	'content-length: ' . strlen($data))
);

$time_start = microtime(true);
$result = curl_exec($ch);
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
curl_close($ch);
$cinfo=json_decode($result,true);
if ($cinfo[0]=="Status: OK" && $cinfo[1]=="MSG: Session Created")
{
	$sid=$cinfo[2];
	$data="sid=$sid&uid=$username";
	echo "\r\nSession Created Successfully!\r\n";
	echo "SID: $sid\r\n";
	echo "Create Session Execution Time: $execution_time\r\n";
	//log_session($db)
	
	$ch=curl_init('https://cs4743.professorvaladez.com/api/query_files');
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array(
	'content-type: application/x-www-form-urlencoded',
	'content-length: ' . strlen($data))
	);
	include("functions.php");
	$time_start = microtime(true);
	$result = curl_exec($ch);
	$time_end = microtime(true);
	$execution_time = ($time_end - $time_start)/60;
	curl_close($ch);
	$cinfo=json_decode($result, true);
	if ($cinfo[0]=="Status: OK"){
		if ($cinfo[1]=="Action: None")
		{
			echo "\r\n No new files to import found \r\n";
			echo "SID: $sid\r\n";
			echo "Username: $username\r\n";
			echo "Query Files Execution Time: $execution_time\r\n";
		}
		else
		{
			$tmp=explode(":", $cinfo[1]);
			$files=explode(",",$tmp[1]);
			echo "Number of new files to import found: ".count($files)."\r\n";
			//log_session($dblink, $username, $sid, "")
			echo "Files:\r\n";
			
			$hostname="localhost";
            $username="webuser";
            $password="CHg_OIfuD[7u[SNR";
            $db="docstorage";
            $dblink=new mysqli($hostname,$username,$password,$db);
            if (mysqli_connect_errno())
            {
                die("Error connecting to database: ".mysqli_connect_error());
            }
			
			foreach($files as $key=>$value)
			{
				$path=$value;
				echo $value."\r\n";
				$tmp=explode("/", $value);
				$file=$tmp[4];
				echo "File: $file\r\n";
				$data="sid=$sid&uid=$username&fid=$file";
				$ch=curl_init('https://cs4743.professorvaladez.com/api/request_file');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'content-type: application/x-www-form-urlencoded',
					'content-length: ' . strlen($data)
				));
				$fileinfo=explode("-", $file);

                $file_number=$fileinfo[0];
                $file_department=$fileinfo[1];
                $file_date=date("Y-m-d_H:i:s");
                $fileType="pdf";
				
				$sql="Insert into `receive_log` (`file_number`,`file_department`,`upload_date`,`file_type`) values ('$file_number','$file_department','$file_date', '$fileType')";
                $dblink->query($sql) or
                    die("Something went wrong with $sql<br>".$dblink->error);
				
				$time_start = microtime(true);
				$result = curl_exec($ch);
				$time_end = microtime(true);
				$execution_time = ($time_end - $time_start)/60;
				$content=$result;

				$fp=fopen("/var/www/html/receive$file","wb");
				fwrite($fp,$content);
				fclose($fp);
				echo "\r\n$file written to file system\r\n";
			}
			echo "Query Files Execution Time: $execution_time\r\n";
		}
		$data="sid=$sid";
		$ch=curl_init('https://cs4743.professorvaladez.com/api/close_session');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'content-type: application/x-www-form-urlencoded',
			'content-length: ' . strlen($data)
		));
		$time_start = microtime(true);
		$result = curl_exec($ch);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;
		curl_close($ch);
		$cinfo=json_decode($result, true);
		if ($cinfo[0]=="Status: OK")
		{
			echo "Session successfully closed!\r\n";
			echo "SID: $sid\r\n";
			echo "Close Session execution time: $execution_time\r\n";
		}
		else
		{
			echo $cinfo[0];
			echo "\r\n";
			echo $cinfo[1];
			echo "\r\n";
			echo $cinfo[2];
			echo "\r\n";
		}
	}
	else
	{
		echo $cinfo[0];
		echo "\r\n";
		echo $cinfo[1];
		echo "\r\n";
		echo $cinfo[2];
		echo "\r\n";
	}
}
else
{
	echo $cinfo[0];
	echo "\r\n";
	echo $cinfo[1];
	echo "\r\n";
	echo $cinfo[2];
	echo "\r\n";
}
?>