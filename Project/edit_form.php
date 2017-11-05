<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Data Base Control Panel</title>
		<?php require_once ('db_info.php'); ?>
	</head>
	<body>

	<h1 align="center">Edit Member</h1>

<?php

	if(isset($_REQUEST['id']))
	{
		$id=trim($_REQUEST['id']);
		$database = "tournament_alpha_list";
		//Data base connection
		$connection = mysql_connect($host,$user,$password);
		if (!$connection )
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($dbname, $connection);
		$result = mysql_query ("SELECT * FROM $database WHERE bcra_no='$id'", $connection);
		$infoarr = mysql_fetch_array($result);
		//Create form with retrieved info
		echo "<form action='update.php' method='post'>";
		echo "<p>First Name: <input type='text' name= 'first_name' value='" .$infoarr['first_name']."' /></p>";

		echo "<p>Last Name: <input type='text' name='last_name' value='" .$infoarr['last_name'] ."' /></p>";
		
		echo "<p>Date of Birth:<br/>"; 
		$date_of_birth = $infoarr['date_of_birth'];
		list($year_of_birth, $month_of_birth, $day_of_birth) = split('-', $date_of_birth, 3);
		echo "	YYYY: <input type='text' name='year_of_birth' value='" .$year_of_birth ."' />";
		$birth_month_options = months($month_of_birth);
		echo "	MM: <select name='month_of_birth'>".$birth_month_options."</select>";
		$birth__day_options = days($day_of_birth);
		echo "	DD: <select name='day_of_birth'>".$birth__day_options."</select></p>";
		//echo "<p>MM: <input type='text' name='month_of_birth' value='" .$month_of_birth ."' /></p>";
		
		echo "<p>*Member No: <input type='text' name='member_no' value='" .$infoarr['member_no'] ."' disabled='disabled' />";
		echo "<input type='hidden' name='hmember_no' value=' " .$infoarr['member_no'] ."' /></p>";     
		
		echo "<p>Member Expiration Date:<br/>"; 
		$member_exp = $infoarr['member_exp'];
		list($year_of_exp, $month_of_exp, $day_of_exp) = split('-', $member_exp, 3);
		echo "	YYYY: <input type='text' name='year_of_exp' value='" .$year_of_exp ."' />";
		$exp_month_options = months($month_of_exp);
		echo "	MM: <select name='month_of_exp'>".$exp_month_options."</select>";
		$exp__day_options = days($day_of_exp);
		echo "	DD: <select name='day_of_exp'>".$exp__day_options."</select></p>";
		//echo "<p>Member Expiration Date: <input type='text' name='member_exp' value='" .$infoarr['member_exp'] ."' /></p>";
		
		echo "<p>T-shirt Size: <input type='text' name='t_shirt_size' value='" .$infoarr['t_shirt_size'] ."' /></p>";
		
		echo "<p>Rank: <input type='text' name='rank' value='" .$infoarr['rank'] ."' /></p>";
		
		echo "<p>Work Phone No: <input type='text' name='work_phone' value='" .$infoarr['work_phone'] ."' /></p>";
		
		echo "<p>Home Phone No: <input type='text' name='home_phone' value='" .$infoarr['home_phone'] ."' /></p>";
		
		echo "<p>Mobile Phone No: <input type='text' name='mobile_phone' value='" .$infoarr['mobile_phone'] ."' /></p>";
		
		echo "<p>Email: <input type='text' name='email' value='" .$infoarr['email'] ."' /></p>";
		
		echo "<p>Address: <input type='text' name='address_city' value='" .str_replace(' ',"&nbsp;",$infoarr["address_city"]) ."' /></p>";
		
		echo "<p>Province: <input type='text' name='province' value='" .$infoarr['province'] ."' /></p>";
		
		echo "<p>Country: <input type='text' name='country' value='" .$infoarr['country'] ."' /></p>";
		
		echo "<p>Postal Code: <input type=\"text\" name=\"postal_code\" value='" .str_replace(' ',"&nbsp;",$infoarr["postal_code"]) ."' /></p>";
		
		echo "<p><input type='hidden' name = 'id'  value='" . $id . "'/> </p>";
		
		echo "<p><input type='submit' value='Update' /></p>";
		
		echo "</form>";
		
		echo "<p><b>*Fields that cannot be changed</b></p>";
		
		echo "<p><b><a href='man_page.php'>Return?</a></b></p>";
		
		mysql_close();
	} 
	else
	{
		print "<h1>Ooops, you did not provide member!</h1>";
	}
	
	/**
		Display months in year.
	*/
	function months($month_selected)
	{
		$options = "";
		for($count = 1; $count <= 12; $count++)
		{
			if($count == $month_selected)
				$options .= "<option selected='selected'>" . $count . "</option>";
			else
				$options .= "<option>" . $count . "</option>";
		}
		return $options;
	}
	/**
		Display days in month.
	*/
	function days($day_selected)
	{
		$options = "";
		for($count = 1; $count <= 31; $count++)
		{
			if($count == $day_selected)
				$options .= "<option selected='selected'>" . $count . "</option>";
			else
				$options .= "<option>" . $count . "</option>";
		}
		return $options;
	}

?>

	</body>
</html>