<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Deletion</title>
<?php require_once ('db_info.php'); ?>
</head>
<body>


<?php

$database = "tournament_alpha_list";
$connection = mysql_connect($host,$user,$password);
if (!$connection )
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("saintpat_database", $connection);
$msg="Deleting:";
if(isset($_REQUEST['id']))
{
  $id=$_REQUEST['id'];
}
else
{
  $id=NULL;
  $msg =$msg." Wrong access. ID is null. <a href='man_page.php'>Try again</a><br>";
}
if($id!=NULL)
{
  $query = "DELETE FROM $database WHERE bcra_no='$id'";
  mysql_query($query,$connection);
  $msg =$msg."</br> Selected record was successfully deleted... <a href='man_page.php'>Return</a>";
}
print "<font color='red'>$msg</font>";
mysql_close($connection);
?>




</body>
</html>