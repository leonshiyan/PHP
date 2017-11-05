<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
        <head>      
		<title>Client Form</title>
		<?php require_once ('db_info.php'); ?>
	</head>

	<body>
    		<h1>Client Form</h1>
        
		<?php 
			//QUEREY INPUT!! 
			if ( isset ($_REQUEST['last_name'], $_REQUEST['first_name'], $_REQUEST['member_no']) )
			{
				$link = mysql_connect($host, $user, $password);	//connects successfully all the time
				if (! $link) 
					die('Could not connect: ' . mysql_error());
				 echo '<p>Connected successfully</p>'; // check if connected 
				
				if(! mysql_select_db($dbname, $link))	//select correct database
					die("Can't use " . $dbname . ": " . mysql_error()); // angelo edit
				echo '<p>Database found.</p>';
					
				$table = "tournament_alpha_list";
				
				// GET INPUTS //angelo edit
				$lastName = trim( $_REQUEST['last_name'] );
				$firstName = trim( $_REQUEST['first_name'] );
				$memberNo = trim( $_REQUEST['member_no'] );
				$yearBirth = trim( $_REQUEST['year_of_birth'] );
				$monthBirth = trim( $_REQUEST['month_of_birth'] );
				$dayBirth = trim( $_REQUEST['day_of_birth'] );
				$workPhone = trim( $_REQUEST['work_phone'] );
				$homePhone = trim( $_REQUEST['home_phone'] );
				$mobilePhone = trim( $_REQUEST['mobile_phone'] );
				$email = trim( $_REQUEST['email'] );
				$address = trim( $_REQUEST['address'] );
				$city = trim( $_REQUEST['city'] );
				$province = trim( $_REQUEST['province'] );
				$country = trim( $_REQUEST['country'] );
				$postalCode = trim( $_REQUEST['postal_code'] );
				
				//echo "<p>Table name: ".$table."</p>";
				// TEST MOST INPUTS // angelo edit
				if( valid_inputs($lastName, $firstName, $yearBirth, $monthBirth, $dayBirth, $memberNo, $workPhone, $homePhone, $mobilePhone, $city, $province, $country, $postalCode) )
				{
					if($dayBirth < 10)
						$dayBirth = '0'.$dayBirth; //if birth day is only 1 digit, add a zero infront
					if($monthBirth < 10)
						$monthBirth = '0'.$monthBirth; // if birth month is only 1 digit, add a zero infront
					$birth_date = "" . $yearBirth . $monthBirth . $dayBirth;
					echo "<p>Date: ".$birth_date."</p>";
					
					$sql = "INSERT INTO `$table` (last_name, first_name, date_of_birth, member_no,
					work_phone, home_phone, mobile_phone, email, 
					address, city, province, country, postal_code) 
					VALUES('$lastName','$firstName', '$birth_date', '$memberNo',
					'$workPhone', '$homePhone', '$mobilePhone', '$email', 
					'$address', '$city', '$province', '$country', '$postalCode')";
					
					if ( ! mysql_query($sql, $link) )
						die('Error: ' . mysql_error());
					
					if (mysql_affected_rows( ) == 1)
						echo "Record was successfully added to database.";
					else
						echo "Record not added: " . mysql_error();
						
				}

				mysql_close( );	//close mysql
			}  
			
			// CHECKS FOR MOST IMPUTS //angelo edit
			/**
				Check whether given inputs are valid.
				@param	$lastName	client surname
				@param	$firstName	client given name
				@param	$yearBirth	year of birth
				@param	$monthBirth	month of birth
				@param	$dayBirth	day of birth
				@param	$memberNo	,member number
				@param	$workPhone	work phone number
				@param	$homePhone	home phone number
				@param	$mobilePhone	mobile phone number
				@param	$province	province of client
				@param	$country	country of client
				@param	$postalCode	postal code of client
				@return	$valid		true or false depending on all inputs are valid
			*/
			function valid_inputs($lastName, $firstName, $yearBirth, $monthBirth, $dayBirth, $memberNo, $workPhone, $homePhone, $mobilePhone, $city, $province, $country, $postalCode)
			{
				$valid = true;	// initially it's true, if one test is false, everything is false
				
				$valid = is_blank($lastName, $firstName,  $memberNo, $valid);	//see any required field is blank
				if(strlen($yearBirth) != 0)
					$valid = isValidDate($yearBirth, $monthBirth, $dayBirth, $valid);	//see whether date is enterd properly
				//check all other parameters that have restrictions
				$valid = is_correct_parameters($memberNo, $workPhone, $homePhone, $mobilePhone, $province, $country, $postalCode, $valid);

				return $valid;	
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
				@param	$memberNo	member number
				@param	$workPhone	work phone number
				@param	$homePhone	home phone number
				@param	$mobilePhone	mobile phone number
				@param	$province	province of client
				@param	$country	country of client
				@param	$postalCode	postal code of client
				@param	$valid		determines whether error is found
				@return	$valid		true or false depending on whether error is found
			*/
			function is_correct_parameters($memberNo, $workPhone, $homePhone, $mobilePhone, $province, $country, $postalCode, $valid)
			{
				//fields entered by user
				$fields = array($memberNo, $workPhone, $homePhone, $mobilePhone, $province, $country, $postalCode);
				//error messages displayed if field input is incorrect
				$error_msgs = array("Member number must be exactly five digits.", "Work phone number entered must be exactly ten digits.", 
					"Home phone number entered must be exactly ten digits.", "Mobile phone number entered must be exactly ten digits.", 
					"Province entered must be exactly two letters.", "Country entered must be exactly three letters.", "Canadian postal code must be entered in format of Let Dig Let Dig Let Dig.");
				//correct parameters for each field
				$parameters = array( "/^[0-9]{5}$/", "/^[0-9]{10}$/", "/^[0-9]{10}$/", "/^[0-9]{10}$/", "/^[A-Za-z]{2}$/", "/^[A-Za-z]{3}$/", "/^([A-Za-z][0-9]){3}$/");
				
				for($count = 0; $count < sizeof($fields); $count++)
				{
					if( strlen($fields[$count]) != 0 && ! preg_match($parameters[$count], $fields[$count]) )
					{
						if( ( 6 == $count && strcasecmp($fields[$count-1], "can") == 0 )	//check postal code only if it is Canadian
						|| ( $count < 6 ) )
						{
							echo "<p> Error! " . $error_msgs[$count] . "</p>";
							$valid = false;
						}
					}
				}
				
				return $valid;
			}
			/** 
				Check that date of birth entered is a valid date.
				@param	$birth_year	year of birth
				@param	$birth_month	month of birth
				@param	$birth_day	day of birth
				@param	$valid		determines whether error is found
				@return	$valid		true or false depending on whether error is found
			*/
			function isValidDate($birth_year, $birth_month, $birth_day, $valid)
			{
				if( isset($birth_year, $birth_month, $birth_day) )
				{
					//check that birth year has four digits
					if (!  preg_match( "/^[0-9]{4}$/", $birth_year) )	
					{
						echo "<p>Error! Year must be entered as YYYY.</p>"; 
						return false;
					}
					//final day of month
					$max_day = get_max_day( (int)$birth_year, (int)$birth_month, (int)$birth_day );
					if ( (int)$birth_day > $max_day )	//check that birth day entered does not exceed days in month
					{
						echo "<p>Error! Day entered cannot be greater than " . $max_day . "</p>"; 
						$valid = false;
					}
				}
				
				return $valid;
			}
			/**
				Get the final day of a given month.
				@param	$birth_year	year of birth
				@param	$birth_month	month of birth
				@param	$birth_day	day of birth
				@return	28 to 31	final day of given month
			*/
			function get_max_day($birth_year, $birth_month, $birth_day)
			{
				if( 4 == $birth_month || 6 == $birth_month || 9 == $birth_month || 11 == $birth_month )
					return 30;	//final day of months April, June, September, and November
                if(2 == $birth_month)
                {
                    if($birth_year % 1000 == 0)
                        return 29;
                    elseif($birth_year % 100 == 0)
                        return 28;
                    elseif($birth_year % 4 == 0)
                        return 29;
                    else
                        return 28;
                }

				return 31;	//final day of January, March, May, July, August, October, December
			}
			/**
				Display months in year.
			*/
			function months()
			{
				$options = "";
				for($count = 1; $count <= 12; $count++)
					$options .= "<option>" . $count . "</option>";
				echo $options;
			}
			/**
				Display days in month.
			*/
			function days()
			{
				$options = "";
				for($count = 1; $count <= 31; $count++)
					$options .= "<option>" . $count . "</option>";
				echo $options;
			}
			
			// end of php              
		?> 

		<form action="" method="post">
			<p>*Last Name: <input type="text" name="last_name" /></p>
			<p>*First Name: <input type="text" name="first_name" /></p>
			<p>*Member No: <input type ="text" name="member_no" /></p>
			<p>
				Date of Birth:<br/> 
				YYYY<input type="text" name="year_of_birth" />
				MM
				<select name="month_of_birth">
					<?php
						months();   //display months in a year
					?> 
				</select>
				DD
				<select name="day_of_birth">
					<?php
						days(); //display days in a month
					?> 
				</select>
			</p>
			<p>
				Work Phone No: <input type ="text" name="work_phone" />
				Home Phone No: <input type ="text" name="home_phone" />
				Mobile Phone No.: <input type="text" name="mobile_phone" />
			</p>
			<p>Email: <input type="text" name="email" /></p>
			<p>Address: <input type="text" name="address" /></p>
			<p>City: <input type="text" name="city" /></p>
			<p>Province: <input type="text" name="province" /></p>
			<p>Country: <input type ="text" name="country" /></p>
			<p>Postal_Code: <input type ="text" name="postal_code" /></p>
			
			<p><input type="submit" value="Add" /></p>
		</form>

		<p>* Required field.</p>

	</body>
</html>