<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update</title>
<?php require_once ('db_info.php'); ?>
</head>

<body>
<h1 align="center">Editing</h1>

<?php
	if ( isset($_POST["id"]) )
	{
		// GET INPUTS //angelo edit
		$lastName = trim( $_REQUEST['last_name'] );
		$firstName = trim( $_REQUEST['first_name'] );
		$memberNo = trim( $_REQUEST['hmember_no'] );
		$workPhone = trim( $_REQUEST['work_phone'] );
		$homePhone = trim( $_REQUEST['home_phone'] );
		$mobilePhone = trim( $_REQUEST['mobile_phone'] );
		$tshirtsize = trim( $_REQUEST['t_shirt_size'] );
		$email = trim( $_REQUEST['email'] );
		$address = trim( $_REQUEST['address_city'] );
		$province = trim( $_REQUEST['province'] );
		$country = trim( $_REQUEST['country'] );
		$postalCode = trim( $_REQUEST['postal_code'] );
		$t_shirt_size = trim( $_REQUEST['t_shirt_size'] );
		$rank = trim( $_REQUEST['rank'] );
		$id = trim( $_REQUEST['id'] );
		$yearBirth = trim($_REQUEST['year_of_birth']);
		$monthBirth = trim($_REQUEST['month_of_birth']);
		$dayBirth = trim($_REQUEST['day_of_birth']);
		$yearExp = trim($_REQUEST['year_of_exp']);
		$monthExp = trim($_REQUEST['month_of_exp']);
		$dayExp = trim($_REQUEST['day_of_exp']);
		
		// TEST MOST INPUTS // angelo edit
		if( valid_inputs($lastName, $firstName, $memberNo, $workPhone, $homePhone, $mobilePhone, $address, $province, $country, $postalCode, $t_shirt_size, $rank ,$yearBirth,$monthBirth,$dayBirth,$yearExp,$monthExp,$dayExp ) )
		{
			$database = "tournament_alpha_list";
			//sql connection
			$connection = mysql_connect($host,$user,$password);
			if (!$connection )
			{
				die('Could not connect: ' . mysql_error());
			}
			//database selected
			mysql_select_db($dbname, $connection);
			
			//Combine seperated birth date into formatted date
			$mem_birth_date = my_date($yearBirth ,$monthBirth,$dayBirth);
			//Combine seperated expire date into formatted date
			$mem_exp_date = my_date($yearExp ,$monthExp,$dayExp);
										
			mysql_query("UPDATE $database SET last_name = '$lastName', first_name = '$firstName',
			member_no = '$memberNo',work_phone = '$workPhone', home_phone = '$homePhone', mobile_phone = '$mobilePhone', email = '$email', 
			address_city = '$address', province = '$province', country = '$country', postal_code = '$postalCode',t_shirt_size = '$tshirtsize',rank='$rank',date_of_birth='$mem_birth_date',member_exp = '$mem_exp_date'
			WHERE bcra_no = '$id' ");
						
			if (mysql_affected_rows( ) == 1)
				echo "Record was successfully updated. </br>";
			else
				echo "Record not update:" . mysql_affected_rows( ) . " record changed</br>";
				
			echo "<h3> <a href='edit_form.php?id=$id'>Return to edit page?</a> </h3>";
					
		}
		else
{
echo "<h3> <a href='edit_form.php?id=$id'>Return to edit page?</a> </h3>";

}
	}  
	// CHECKS FOR MOST IMPUTS //angelo edit//Yan edit
	/**
		Check whether given inputs are valid.
		@param	$lastName		client surname
		@param	$firstName		client given name
		@param	$yearBirth		year of birth
		@param	$monthBirth		month of birth
		@param	$dayBirth		day of birth
		@param	$yearExp		year of expire
		@param	$monthExp		month of expire
		@param	$dayExp		day of expire
		@param	$memberNo		,member number
		@param	$workPhone		work phone number
		@param	$homePhone		home phone number
		@param	$mobilePhone		mobile phone number
		@param	$province		province of client
		@param	$country		country of client
		@param	$postalCode		postal code of client
		@param	$t_shirt_size	the size of the client's t-shirt
		@param	$rank			client's rank
		@return	$valid			true or false depending on all inputs are valid
	*/
	function valid_inputs($lastName, $firstName, $memberNo, $workPhone, $homePhone, $mobilePhone, $address, $province, $country, $postalCode, $t_shirt_size, $rank ,$yearBirth,$monBirth,$dayBirth,$yearExp,$monthExp,$dayExp)
	{
		$valid = true;	// initially it's true, if one test is false, everything is false
		
		$valid = is_blank($lastName, $firstName,  $memberNo, $valid);	//see any required field is blank
		//check all other parameters that have restrictions
		$valid = is_correct_parameters($memberNo, $workPhone, $homePhone, $mobilePhone, $province, $country, $postalCode, $t_shirt_size, $rank, $valid);
		$valid = is_correct_date($yearBirth,$monBirth,$dayBirth,$valid);
		$valid = is_correct_date($yearExp,$monthExp,$dayExp,$valid);
		return $valid;	
	}
	
	/**
		Check if the date is correct
		@param	$year	number of year
		@param	$month	number of month
		@param	$day	number of day
		@param	$valid		determines whether error is found
		@return	$valid		true or false depending on whether error is found
	*/
	function is_correct_date($year,$month,$day,$valid)
	{
		if( strlen($year) != 0 && (strlen($year) > 4 || !is_numeric($year)) )
		{
			echo "<p> Year may only contain digits, at most four. </p>";
			$valid = false;
		}
		
		if( strlen($year) == 0  )
		{
			echo "<p> Year cannot be blank! </p>";
			$valid = false;
		}
		
		if($month == 4 ||$month == 6 ||$month == 9 ||$month == 11 )
		{
			if($day > 30)
			{
				echo "<p> Days cannot be more than 30!</p>";
			}
		}
		
		if($month == 2)
		{
			if(date('L', strtotime("$year-01-01")))
			{
				if($day > 29)
				{
					echo "<p> Days cannot be more than 29!</p>";
					$valid = false;
				}
			}
			elseif($day > 28)
			{
				echo "<p> Days cannot be more than 28!</p>";
				$valid = false;
			}
		}
		
		return $valid;
	}
	/**
		Convert numberical date into formatted date
		@param	$year	number of year
		@param	$month	number of month
		@param	$day	number of day
		@return	$formatted_date the formatted date string
	*/
	function my_date ($year ,$month,$day)
	{
		if($day < 10)
		$day = '0'.$day; //if birth day is only 1 digit, add a zero infront
		if($month < 10)
		$month = '0'.$month; // if birth month is only 1 digit, add a zero infront
		$formatted_date = "" . $year . $month . $day;
		return  $formatted_date;
	}


	/**
		Check that required fields are not blank.
		@param	$lastName	client surname
		@param	$firstName	client given name
		@param	$memberNo	member number
		@param	$valid		determines whether error is found
		@return	$valid		true or false depending on whether error is found
	*/
	function is_blank($lastName, $firstName, $memberNo, $valid)
	{
		$fields = array($lastName, $firstName, $memberNo);
		$error_msgs = array("Last name", "First name", "Member number");
		
		for($count = 0; $count < sizeof($fields); $count++)
		{
			if( strlen($fields[$count]) == 0 )
			{
				echo "<p>" . $error_msgs[$count] . " field cannot be left blank.</p>";
				$valid = false;
			}
		}
		
		return $valid;
	}
	/**
		Check that all non-required fields are properly entered.
		@param	$memberNo		member number
		@param	$workPhone		work phone number
		@param	$homePhone		home phone number
		@param	$mobilePhone		mobile phone number
		@param	$province		province of client
		@param	$country		country of client
		@param	$postalCode		postal code of client
		@param	$t_shirt_size	the size of the client's t-shirt
		@param	$rank			client's rank
		@param	$valid			determines whether error is found
		@return	$valid			true or false depending on whether error is found
	*/
	function is_correct_parameters($memberNo, $workPhone, $homePhone, $mobilePhone, $province, $country, $postalCode, $t_shirt_size, $rank, $valid)
	{
		//fields entered by user
		$fields = array($memberNo, $workPhone, $homePhone, $mobilePhone, $province, $country);
		//error messages displayed if field input is incorrect
		$error_msgs = array("Member number must be exactly five digits.", "Work phone number entered must be exactly ten digits.", 
			"Home phone number entered must be exactly ten digits.", "Mobile phone number entered must be exactly ten digits.", 
			"Province entered must be exactly two letters.", "Country entered must be exactly three letters.");
		//correct parameters for each field
		$parameters = array( "/^[0-9]{5}$/", "/^[0-9]{10}$/", "/^[0-9]{10}$/", "/^[0-9]{10}$/", "/^[A-Za-z]{2}$/", "/^[A-Za-z]{3}$/");
		
		for($count = 0; $count < sizeof($fields); $count++)
		{
			if( strlen($fields[$count]) != 0 && ! preg_match($parameters[$count], $fields[$count]) )
			{
				echo "<p> Error! " . $error_msgs[$count] . "</p>";
				$valid = false;
			}
		}
		if(strlen($postalCode) != 0 && strlen($postalCode) > 7)
		{
			echo "<p> Error! Postal code can be no more than seven characters. </p>";
			$valid = false;
		}
		if( strlen($t_shirt_size) != 0 && (strlen($t_shirt_size) > 2 || ! ctype_alpha($t_shirt_size)) )
		{
			echo "<p> Error! T-shirt size must all letters, no more than two. </p>";
			$valid = false;
		}
		if( strlen($rank) != 0 && (strlen($rank) > 3 || ! is_numeric($rank)) ) 
		{
			echo "<p> Rank may only contain digits, at most three. </p>";
			$valid = false;
		}
		
		return $valid;
	}
				
?>


</body>
</html>