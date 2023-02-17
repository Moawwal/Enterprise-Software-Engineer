<?php
$username="kyo537";
$password='M9R7tgYrcmJ8pQC6';
$data="username=$username&password=$password";
$ch=curl_init('https://cs4743.professorvaladez.com/api/query_files');
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
if ($cinfo[0]=="Status: OK")
{
	$files=explode(",", $cinfo[1]);
	foreach($files as $key=>$value)
	{
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
		$time_start = microtime(true);
		$result = curl_exec($ch);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;
		$content=$result;
		
		$fp=fopen("/var/www/html/receive/$file","wb");
		fwrite($fp,$content);
		fclose($fp);
		echo "\r\n$file written to file system\r\n";
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