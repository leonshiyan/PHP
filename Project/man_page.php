<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Data Base Control Panel</title>
		<?php require_once ('db_info.php'); ?>
	</head>
	<body>
        
		<h1 align="center">Database Control</h1>
                
		<div>
			<form action="" method="post"> 
				<p>
					Search key: <input type="text" name="key" />
				</p>
				<p>
					Search member by:
					<select name="menu">
						<option value="0" selected>(please select:)</option>
						<option value="1">last name</option>
						<option value="2">first name</option>
						<option value="3">member number</option>
						<option value="4">date of birth</option>
						<option value="5">work phone number</option>
						<option value="6">home phone number</option>
						<option value="7">mobile phone number</option>
						<option value="8">e-mail </option>
						<option value="9">province</option>
						<option value="10">postal code</option>
						<option value="11">country</option>
						<option value="12">entry date</option>
						<option value="13">entry time</option>
						<option value="14">expire date</option>
						<option value="15">rank</option>
					</select>
				</p>
				<p><input type="submit" value="Search" /></p>
			</form>
		</div>
                
		<h3>
			<a href='client_form.php'>Want to add a member?</a>
		</h3>
                
<?php

	$database = "tournament_alpha_list";
            
	//Data base connection
        $connection = mysql_connect($host,$user,$password);
	if (!$connection )
        {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($dbname, $connection);
                
	$result = mysql_query ("SELECT * FROM $database", $connection);
            
	$target="all";
	if(isset($_POST['menu'],$_POST['key']))
	{
		$targetValue = trim($_POST['menu']);
                $key = trim($_POST['key']);
                //print "key is entered as $key </br>";
                switch($targetValue):
			case "1":
				$target = "last_name";
			break;
			case "2":
				$target = "first_name";
			break;
			case "3":
				$target = "member_no";
			break;
			case "4":
				$target = "date_of_birth";
			break;
			case "5":
				$target = "work_phone";
			break;
			case "6":
				$target = "home_phone";
			break;
			case "7":
				$target = "mobile_phone";
			break;
			case "8":
				$target = "email";
				break;
			case "9":
				$target = "province";
			break;
			case "11":
				$target = "country";
			break;
			case "10":
				$target = "postal_code";
			break;
			case "12":
				$target = "entry_date";
			break;
			case "13":
				$target = "entry_time";
			break;
			case "14":
				$target = "member_exp";
			break;
			case "15":
				$target = "rank";
			break;
			default:
				$target = "all";  //Defaule search target
                endswitch;
                //print "Target is $target </br>";
	}
            
	//Display the table of match targets
                
	$num = mysql_num_rows($result);
            
	print "<table border='1'>
		<tr><td align='center'>Actions</td>
			<td align='center'>ID #</td>
			<td align='center'>Last Name</td>
			<td align='center'>First Name</td>
			<td align='center'>Date of Birth</td>
			<td align='center'>T-shirt Size</td>
			<td align='center'>Rank</td>
			<td align='center'>Member No.</td>
			<td align='center'>Member Expiration Date</td>
			<td align='center'>Work Phone</td>
			<td align='center'>Home Phone</td>
			<td align='center'>Mobile Phone</td>
			<td align='center'>Email</td>
			<td align='center'>Address and City</td>
			<td align='center'>Province</td>
			<td align='center'>Country</td>
			<td align='center'>Postal Code</td>
			<td align='center'>Entry by</td>
			<td align='center'>Entry date</td>
			<td align='center'>Entry time</td>
                </tr>";
                
	if(strcmp($target, "all") == 0)/*$target=="all"*/
	{
		for( $ii = 0 ; $ii < $num ; $ii++ )
                {
			$first_name = mysql_result($result,$ii,"first_name");
			$last_name = mysql_result($result,$ii,"last_name");
			$date_of_birth = mysql_result($result,$ii,"date_of_birth");
			$t_shirt_size = mysql_result($result,$ii,"t_shirt_size");
			$rank = mysql_result($result,$ii,"rank");
			$member_no = mysql_result($result,$ii,"member_no");
			$member_exp = mysql_result($result,$ii,"member_exp");
			$work_phone = mysql_result($result,$ii,"work_phone");
			$home_phone = mysql_result($result,$ii,"home_phone");
			$mobile_phone = mysql_result($result,$ii,"mobile_phone");
			$email = mysql_result($result,$ii,"email");
			$address_city = mysql_result($result,$ii,"address_city");
			$province = mysql_result($result,$ii,"province");
			$country = mysql_result($result,$ii,"country");
			$postal_code = mysql_result($result,$ii,"postal_code");
			$entry_by = mysql_result($result,$ii,"entry_by");
			$entry_date = mysql_result($result,$ii,"entry_date");
			$entry_time = mysql_result($result,$ii,"entry_time");
			$id = mysql_result($result,$ii,"bcra_no");
			
			print "<tr>	
				<td><a href='deleteRecord.php?id=$id'>Delete ,</a>
				    <a href='edit_form.php?id=$id'>Edit </a>
				</td>
				<td>$id</td>
				<td>$last_name</td>
				<td>$first_name</td>
				<td>$date_of_birth</td>
				<td>$t_shirt_size</td>
				<td>$rank</td>
				<td>$member_no</td>
				<td>$member_exp</td>
				<td>$work_phone</td>
				<td>$home_phone</td>
				<td>$mobile_phone</td>
				<td>$email</td>
				<td>$address_city</td>
				<td>$province</td>
				<td>$country</td>
				<td>$postal_code</td>
				<td>$entry_by</td>
				<td>$entry_date</td>
				<td>$entry_time</td>
			</tr>";
		}
		print "</table>";
	}
	else
	{
                $notfound = true;
                for( $ii = 0 ; $ii < $num ; $ii++ )
                {
			if($key == mysql_result($result,$ii,$target))
			{
				$notfound = false;
				$first_name = mysql_result($result,$ii,"first_name");
				$last_name = mysql_result($result,$ii,"last_name");
				$date_of_birth = mysql_result($result,$ii,"date_of_birth");
				$t_shirt_size = mysql_result($result,$ii,"t_shirt_size");
				$rank = mysql_result($result,$ii,"rank");
				$member_no = mysql_result($result,$ii,"member_no");
				$member_exp = mysql_result($result,$ii,"member_exp");
				$work_phone = mysql_result($result,$ii,"work_phone");
				$home_phone = mysql_result($result,$ii,"home_phone");
				$mobile_phone = mysql_result($result,$ii,"mobile_phone");
				$email = mysql_result($result,$ii,"email");
				$address_city = mysql_result($result,$ii,"address_city");
				$province = mysql_result($result,$ii,"province");
				$country = mysql_result($result,$ii,"country");
				$postal_code = mysql_result($result,$ii,"postal_code");
				$entry_by = mysql_result($result,$ii,"entry_by");
				$entry_date = mysql_result($result,$ii,"entry_date");
				$entry_time = mysql_result($result,$ii,"entry_time");
				$id = mysql_result($result,$ii,"bcra_no");
			
				print "<tr>	
				    <td><a href='deleteRecord.php?id=$id'>Delete ,</a>
					<a href='modifyRecord.php?id=$id'>Modify </a>
				    </td>
				    <td>$id</td>
				    <td>$last_name</td>
				    <td>$first_name</td>
				    <td>$date_of_birth</td>
				    <td>$t_shirt_size</td>
				    <td>$rank</td>
				    <td>$member_no</td>
				    <td>$member_exp</td>
				    <td>$work_phone</td>
				    <td>$home_phone</td>
				    <td>$mobile_phone</td>
				    <td>$email</td>
				    <td>$address_city</td>
				    <td>$province</td>
				    <td>$country</td>
				    <td>$postal_code</td>
				    <td>$entry_by</td>
				    <td>$entry_date</td>
				    <td>$entry_time</td>
				</tr>";
			}
                }
                print "</table>";
                if($notfound == true)
                {
			print "<h1>Oops, target not found!<h1>";
                }
                mysql_close( );	//close mysql
	}
        
?>
        
        </body>
</html>