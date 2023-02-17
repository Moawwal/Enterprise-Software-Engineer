<?php
$page="report.php";
include("functions.php");
$dblink=db_connect("docstorage");
$sql="Select distinct file_number from `receive_log`";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
echo '<h1>First part</h1>';
while ($data=$result->fetch_array(MYSQLI_ASSOC))
{
	$count=$count+1;
	echo '<div>Loan Number: '.$data['fileNumber'].'</div>';
	$loanArray[]=$data['file_number'];
}
echo '<br><div>Number of Total Unique Loan Numbers: '.$count.'</div>';
echo '<hr>';
echo '<h1>Part 2</h1>';

echo '<div>Current size received from API is 16.0kb</div>';

echo '<hr>';
echo '<h1>Second part</h1>';


foreach($loanArray as $key=>$value)
{
	$sql="Select count(`file_name`) from `receive_log` where `file_name` like '%$value%'";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_NUM);
	echo '<div>Loan number: '.$value.' has '.$tmp[0].' number of documents</div>';
}

echo '<hr>';
echo '<h1>Fourth part</h1>';

echo '<h3>Loan Numbers With Missing Department</h3>';

$dblink->close();

$dblink=db_connect("docstorage");
$sql="SELECT file_number, file_department FROM `received_log` WHERE 1";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
$count=0;
while ($data=$result->fetch_array(MYSQLI_ASSOC))
{
	$loanarray[]=$data['file_number'];
	$loandep[]=$data['file_department'];
}
foreach($loandep as $key=>$value)
{
	if ($value == "Credit" && $value == "Closing" && $value == "Title" && $value == "Financial" && $value == "Personal" && $value == "Internal" && $value == "Legal" && $value == "Other")
	{
		$loanalldep[]=$loanarray[$count];
		
	}
	else
	{
		$loanmissdepnum[]=$loanarray[$count];
		$loanmissdep[]=$value;
	}
	
	$count=$count+1;
}
echo '<h4>Loan Number missing at least one document:</h4>';
$count=0;
foreach($loanmissdep as $key=>$value)
{
	echo '<div>Loan Number-'.$loanarray[$count].' and Missing Department-'.$value.'</div>';
	$count=$count+1;
}

if ($loanalldep!=NULL)
{
	echo '<h4>Loan Number with all documents:</h4>';
	foreach($loanalldep as $key=>$value)
	{
		echo '<div>Loan Number-'.$value.'</div>';
	}
		
}
else
{
	echo '<h4>No Loan Number with all documents exist yet</h4>';
}

$creditcount=0;
$closecount=0;
$titlecount=0;
$financialcount=0;
$personalcount=0;
$internalcount=0;
$legalcount=0;
$othercount=0;

foreach($loandep as $key=>$value)
{
	if ($value == "Credit")
	{
		$creditcount=$creditcount+1;
	}
	elseif ($value == "Closing")
	{
		$closecount=$closecount+1;
	}
	elseif ($value == "Title")
	{
		$titlecount=$titlecount+1;
	}
	elseif ($value == "Financial")
	{
		$financialcount=$financialcount+1;
	}
	elseif ($value == "Personal")
	{
		$personalcount=$personalcount+1;
	}
	elseif ($value == "Internal")
	{
		$internalcount=$internalcount+1;
	}
	elseif ($value == "Legal")
	{
		$legalcount=$legalcount+1;
	}
	elseif ($value == "Other")
	{
		$othercount=$othercount+1;
	}
}

echo '<div>Total for Credit: '.$creditcount.'</div>';
echo '<div>Total for Closing: '.$closecount.'</div>';
echo '<div>Total for Title: '.$titlecount.'</div>';
echo '<div>Total for Financial: '.$financialcount.'</div>';
echo '<div>Total for Personal: '.$personalcount.'</div>';
echo '<div>Total for Internal: '.$internalcount.'</div>';
echo '<div>Total for Legal: '.$legalcount.'</div>';
echo '<div>Total for Other: '.$othercount.'</div>';


?>