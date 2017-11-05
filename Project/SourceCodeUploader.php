<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
        <head>      
		<title>Source Code Uploader</title>
		<?php require_once ('db_info.php'); ?>
	</head>

	<body>
    		<h1 style="text-align: center;">Source Code Uploader</h1>
        
		<?php 	//beginning of php
			
		$doc = new DOMDocument();
		@$doc->loadHTMLFile("source-code.htm"); //point to source code
		$count_rows = 0;    //count rows in table
	
		global $client_info;
		$client_info = array(array()); //store client info into an array 
		global $headings;
		$headings = "";
			
		$tr_elements = $doc->getElementsByTagName("tr");    //extract all rows in table
		if (! is_null($tr_elements)) // search 
			$num_fields = table_rows($count_rows, $tr_elements);	//get number of fields in database table
		
		//echo $num_fields;
		test_loop($num_fields);
		//check_cells($num_fields);
		//insert($num_fields, $host, $user, $password, $dbname);
		function check_cells($num_fields)
		{
			global $client_info;
			
			for($jj = 0; $jj < sizeof($client_info); $jj++)
			{
				//echo "birthdate " . $client_info[$jj][2]."<br>";
				if(! is_numeric($client_info[$jj][2]) || strlen($client_info[$jj][2]) != 8)
					die("birthdate must be numeric");
				//echo "t-shirt size" . $array[$jj][3]."<br>";
				//if( (!ctype_alpha($array[$jj][3]) && strlen($array[$jj][3]) != 0) || strlen($array[$jj][3]) > 2 )
				//	die("t-shirt size must be all letter, no more than two.");
				//echo "rank: " . $array[$jj][4]."<br>";
				//if( ! is_numeric($array[$jj][4]) )
				//	die("rank must be a number.");
				//echo "member number: " . $array[$jj][5]."<br>";
				//if( ! is_numeric($array[$jj][5]) )
				//	die("member number.");
				//echo "member expiration date: " . $array[$jj][6]."<br>";
				//if(! is_numeric($array[$jj][6]) && strlen($array[$jj][6]) != 0)
				//	die("member expiration date must be a number.");
				//echo "work number: " . $array[$jj][7]."<br>";
				//if(! is_numeric($array[$jj][7]) && strlen($array[$jj][7]) != 0)
					//die("work number must be a number.");
				//echo "home number: " . $array[$jj][7]."<br>";
				//if(! is_numeric($array[$jj][7]) && strlen($array[$jj][7]) != 0)
				//	die("home number must be a number.");
				//echo "province: " . $array[$jj][12]."<br>";
				//if(! ctype_alpha($array[$jj][12]) || strlen($array[$jj][12]) != 2)
				//	die("province must be two letters.");
				//echo "postal code: " . $array[$jj][13]."<br>";
				//if(! ctype_alnum($array[$jj][13]) && strlen($array[$jj][13]) != 7)
				//	die("Postal code must be seven alpha numeric.");
				//echo "country: " . $array[$jj][14]."<br>";
				//if(! ctype_alpha($array[$jj][14]) && strlen($array[$jj][14]) != 3)
				//	die("Country must be three characters.");
				//echo "entry date: " . $array[$jj][16]."<br>";
				//if(! is_numeric($array[$jj][16]) && strlen($array[$jj][16]) != 0)
					//die("Entry date typed incorrectly.");
			}
		}
		/**
			Insert customer information into database table.
			@param	$num_fields		number of fields in source code
		*/
		function insert($num_fields, $host, $user, $password, $dbname)
		{
			global $client_info;
			
			$link = mysql_connect($host, $user, $password); // connects successfully all the time
			if (! $link) 
				die('Could not connect: ' . mysql_error());
			echo '<p>Connected successfully</p>'; // check if connected 
			
			if(! mysql_select_db($dbname, $link))	//select correct database
				die("Can't use " . $dbname . ": " . mysql_error()); // angelo edit
			echo "<p>Database found</p>";
		
			$table = "tournament_alpha_list";	//name of table within database
			
			for($jj = 0; $jj < $num_fields-1; $jj++)
			{
			//	echo "<p>" . $jj . " " . $array[0][$jj] . "</p>";
				$last_name = $client_info[$jj][0];
				$first_name = $client_info[$jj][1];
				$date_of_birth = $client_info[$jj][2];
				$t_shirt_size = $client_info[$jj][3];
				$rank = $client_info[$jj][4];
				$member_no = $client_info[$jj][5];
				$member_exp = $client_info[$jj][6];
				$work_phone = $client_info[$jj][7];
				$home_phone = $client_info[$jj][8];
				$mobile_phone = $client_info[$jj][9];
				$email = $client_info[$jj][10];
				$address_city = $client_info[$jj][11];
				$province = $client_info[$jj][12];
				$postal_code = $client_info[$jj][13];
				$country = $client_info[$jj][14];
				$entry_by = $client_info[$jj][15];
				$entry_date =  $client_info[$jj][16];
				$entry_time = $client_info[$jj][17];
				//echo "<p>entry date".$entry_date."</p>";
				
				$insert = "INSERT INTO `$table` (last_name, first_name, date_of_birth, t_shirt_size, rank, member_no, member_exp, work_phone, home_phone, mobile_phone, email, address_city, province, country, postal_code, entry_by, entry_date, entry_time) 
					VALUES('$last_name', '$first_name', '$date_of_birth', '$t_shirt_size', $rank, $member_no, '$member_exp', '$work_phone', '$home_phone', '$mobile_phone', '$email', '$address_city', '$province', '$country', '$postal_code', '$entry_by', '$entry_date', '$entry_time')";
					
				if ( ! mysql_query($insert, $link) )
					die('Error: ' . mysql_error());
				
				if (mysql_affected_rows( ) == 1)
					echo "<p>Record was successfully added to database.</p>";
				else
					echo "<p>Record not added: " . mysql_error() . "</p>";
			}
			
			mysql_close( );
		}
		/**
			Loop through array and echo to see if client info was uploaded properly to array
			@param	$num_fields		number of fields in source code
		*/
		function test_loop($num_fields)
		{
			global $client_info;
			
			$table = "<table border = '1'>";
			$table_headings = array("Last Name", "First Name", "Date of Birth", "T-shirt Size", "Rank", "Member No.", "Member Expiration Date", "Work Phone", "Home Phone", "Mobile Phone", "Email", "Address and City", "Province", "Postal Code", "Country", "Entry By", "Entry Date", "Entry time");
			
			//create table header
			$row = "<tr>";
			for($jj = 0; $jj < sizeof($table_headings); $jj++)
				$row .= "<th>" . $table_headings[$jj] . "</th>";
			$row .= "</tr>";
			$table .= $row;

			$rows = "";
			for($i = 0;$i < sizeof($client_info); $i++)
			{
				$rows .= "<tr>";
				for($j = 0; $j < $num_fields ; $j++)
				{
					$rows .= "<td>" . $client_info[$i][$j] . "</td>" ;
				}
				$rows .= "</tr>";
				
			}
			$table .= $rows . "</table>";
			
			echo $table;
		}
		/** 
			Loop through all rows in a table.
			@param	$count_rows		count rows in table
			@param	$tr_elements		table row elements
			@return	$num_fields		number of fields within table
		*/
		function table_rows($count_rows, $tr_elements)
		{
			global $array;
			global $headings;
			$count_records = 0;    //count customer records in table
			
			foreach ($tr_elements as $tr_element)
			{
				if($count_rows++ % 2 == 1)  //skip odd numbered rows
					$count_records++;
				
				$td_elements = $tr_element->getElementsByTagName("td");   //extract all cells in row
				if ( ! is_null($td_elements) )
				{
					if(0 == $count_rows - 1)
						$headings .= get_table_header($td_elements);
					if( $tr_element->getAttribute("valign") == "top" )
					{
						$tmp = table_cells($count_records, $tr_element, $td_elements);	//extract cells in table row
						if($tmp != 0)
							$num_fields = $tmp;
					}
				}
			}
			//echo $count_records;
	
			return $num_fields;	//return number of customer fields
		}
		/**
			Get headings in table of source code.
			@param	$td_elements		cells in row
			@return	$row			row in table
		*/
		function get_table_header($td_elements)
		{
			global $client_info;
			$count_cells = 0;   //count table cells in header
			
			$row = "<tr>";
			foreach ($td_elements as $td_element)
			{
				if($count_cells > 0)
					$row .= "<th>" . $td_elements->item($count_cells)->nodeValue . "</th>";
				$count_cells++;
			}
			$row .= "</tr>";
			
			return $row;
		}
		/** 
			Loop through all cells in table row.
			@param	$record_index	index of client record
			@param	$tr_element		table row
			@param	$td_elements		cells within a table row
			@return	$num_fields		number of fields within table
		*/
		function table_cells($record_index, $tr_element, $td_elements)
		{
			global $array;
			
			$num_fields = 0;
			$count_cells = 0;	//count table cells in a table row
			$count_inner_fields = 0;	//count fields within a table cell of the source code
				
			foreach ($td_elements as $td_element)
			{
				if( $td_element->getAttribute("class") != "horizontalLine" )    //skip this cell
				{
					//echo $record_index . " " . $td_elements->item($count_columns)->nodeValue . "<br>";
					if($count_cells != 0)   //go into all table cells except first
						$count_inner_fields = table_fields($record_index-1, $count_cells, $count_inner_fields, $td_elements);
					
					$count_cells++;
					$num_fields = $count_cells + $count_inner_fields - 1;
				}
			}
			return $num_fields;	//return number of customer fields
		}
		/** 
			Extact customer id from source code table fields.
			@param	$record_index	index of client record
			@param	$field_index		index of table field
			@param	$inner_field_index	index of client fields located within a table field
			@param	$td_elements		cells within a table row
			@return	$inner_field_index	index of table field
		*/
		function table_fields($record_index, $field_index, $inner_field_index, $td_elements)
		{
			global $client_info;

			$cell = $td_elements->item($field_index)->nodeValue;	//get cell from source code
			$cell = trim($cell);	//trim spaces off beginning and end
			$length = strlen($cell);	//get length of text in table cell
			$field_index --;
			//echo $record_index . " )(" . ($field_index ) . ") " . $cell . "<br>";
			
			if(2 == $field_index)	//extract birthdate and shirt size
				$inner_field_index = set_birthdate_tshirt_size($cell, $record_index, $inner_field_index, $field_index, $length);
			elseif(3 == $field_index)	//don't add divisions field
				return $inner_field_index - 1;
			elseif(4 == $field_index)	//extract rank
				set_member_rank($cell, $record_index, $inner_field_index, $field_index, $length);
			elseif(5 == $field_index)	//extract member number
				set_member_num($cell, $record_index, $inner_field_index, $field_index, $length);
			elseif(6 == $field_index)	//extract expiration date
				set_expiration_date($cell, $record_index, $inner_field_index, $field_index, $length);
			elseif(7 == $field_index)	//extract phone numbers
				$inner_field_index = set_phone_nums($cell, $record_index, $inner_field_index, $field_index, $length);
			elseif(9 == $field_index)	//extract location
				$inner_field_index = set_location($cell, $record_index, $inner_field_index, $field_index, $length);
			elseif(10 == $field_index)	//user and datea and time entered
				$inner_field_index = set_user_time($cell, $record_index, $inner_field_index, $field_index, $length);
			else    //extract names
				$client_info[$record_index][$field_index+$inner_field_index] = $cell;
				
			return $inner_field_index;
		}
		/** 
			Extract and set the customer's birthdate and t-shirt size.
			@param	$dob_tshirt	text within date of birth and t-shirt cell
			@param	$record_index          index of client record
			@param	$count_inner_fields	counts client fields within table field
			@param	$field_index		index of table field
			@param	$length		number of characters within table cell
			@return	$count_inner_fields	counts client fields within table field
		*/
		function set_birthdate_tshirt_size($dob_tshirt, $record_index, $count_inner_fields, $field_index, $length)
		{
			global $client_info;
                
			$birth_date;
			//split into parameters birth month, birth day, and birth year and t-shirt size
			list($month, $day, $year_size) = split('/', $dob_tshirt, 3);

			$birth_date = get_date($dob_tshirt, $length);  //get the client's date of birth
			$client_info[$record_index][$field_index + $count_inner_fields++] = $birth_date;
			
			$tshirt_size = substr($year_size, 4);   //get client's t-shirt size
			$client_info[$record_index][$field_index + $count_inner_fields] = $tshirt_size;
			//echo $tshirt_size."<br><br>";
                
			return $count_inner_fields;
		}
		/**
			Get a date in the format of YYYYMMDD.
			@param	$date		date before it is formatted to YYYYMMDD
			@param	$first_index	first index of date
                        @return	$date		date after it is formatted to YYYYMMDD
		*/
		function get_date($date, $length)
		{
			list($month, $day, $year) = split('/', $date, 3);
			//echo "date " .$date."<br>";
			$year = substr($year, 0, 4);
			if($day < 10)   //add a zero digit if day less than ten
				$day = '0' . $day;
			//echo $day."<br>";
			if($month < 10) //add a zero digit if month less than ten
				$month = '0' . $month;
			$date = $year.$month.$day;
			
			if(! is_numeric($date))     //if date is not numeric, make cell blank
				$date = "";
			
			return $date;
		}
		/**
			Extract and set the customer's rank.
			@param	$rank		       	client's rank number
			@param	$record_index	        index of client record
			@param	$inner_field_index	index of client fields within table field
			@param	$field_index		index of table field
			@param	$length		        number of characters within table cell
		*/
		function set_member_rank($rank, $record_index, $inner_field_index, $field_index, $length)
		{
			global $client_info;
 
			$jj;
			for($jj = 0; $jj < $length; $jj++)  //loop until you reach a non-numeric character
				if(! is_numeric(substr($rank, $jj, 1)) )
				{
					$rank = substr($rank, 0, $jj);
					break;
				}
                    
			$client_info[$record_index][$field_index+$inner_field_index] = $rank;
			//echo $rank."<br>";
		}
		/**
			Extract and set the customer's member number.
			@param	$member_no		client's member ID number
			@param	$record_index	        index of client record
			@param	$inner_field_index	index of client fields within table field
			@param	$field_index		index of table field
			@param	$length	       	        number of characters within $member_no
		*/
		function set_member_num($member_no, $record_index, $inner_field_index, $field_index, $length)
		{
			global $client_info;
                
			$member_no = substr($member_no, 0, 5);  //ensure member number is exactly five characters
			$client_info[$record_index][$field_index+$inner_field_index] = $member_no;
			//echo $member_no."<br>";    
		}
		/**
			Extract and set the customer's membership expiration date.
			@param	$member_exp		date client's membership expires
			@param	$record_index	        index of client record
			@param	$inner_field_index	index of client fields within table field
			@param	$field_index		index of table field
			@param	$length		        number of characters within $member_exp
		*/
		function set_expiration_date($member_exp, $record_index, $inner_field_index, $field_index, $length)
		{
			global $client_info;
			
			$expiration_date = get_date($member_exp, $length);
			$client_info[$record_index][$field_index+$inner_field_index] = $expiration_date;
		}
		/**
			Extract and set the client's work, home, and mobile phone number.
			@param	$phone_nums		client's phone numbers
			@param	$record_index       	index of client record
			@param	$count_inner_fields	counts number of client fields within table field
			@param	$field_index		index of table field
			@param	$length		        number of characters within $phone_nums
			@return	$count_inner_fields	counts number of client fields within table field
		*/
		function set_phone_nums($phone_nums, $record_index, $count_inner_fields, $field_index, $length)
		{
			global $client_info;
			
			$phone_type = "Work";
			$work_phone_num = get_phone_num($phone_nums, $length, $phone_type, strlen($phone_type));
			$client_info[$record_index][$field_index+$count_inner_fields++] = $work_phone_num;
			
			$phone_type = "Home";
			$home_phone_num = get_phone_num($phone_nums, $length, $phone_type, strlen($phone_type));
			$client_info[$record_index][$field_index+$count_inner_fields++] = $home_phone_num;
			
			$phone_type = "Mobile";
			$home_phone_num = get_phone_num($phone_nums, $length, $phone_type, strlen($phone_type));
			$client_info[$record_index][$field_index+$count_inner_fields] = $home_phone_num;
			
			return $count_inner_fields;
		}
		/**
			Get a client's phone number.
			@param	$phone_nums		client's phone numbers
			@param	$cell_length		number of characters within $phone_nums
			@param	$phone_type		type of phone (i.e. work, home, mobile)
			@param	$phone_type_length	number of digits in phone number
			@return	$phone_num		phone number of client
		*/
		function get_phone_num($phone_nums, $cell_length, $phone_type, $phone_type_length)
		{
			$break = false;	//determines whether to break from loop
			$phone_num = "";
				
			for($jj = 0; $jj < $cell_length - 10; $jj ++)
			{
				//check whether type of phone matches "Home", "Mobile", or "Work"
				if( strcmp( substr($phone_nums, $jj, $phone_type_length), $phone_type ) == 0 )
				{
					$kk;
					//loop until you find the first digit of the phone number
					for($kk = $jj; $kk < $cell_length - 10 && ! is_numeric( substr($phone_nums, $kk, 1) ); $kk++){}
					if($kk < $cell_length - 10)
					{
						$phone_num = substr($phone_nums, $kk, 10);
						//echo substr($cell, $kk, 10)."<br>";
						$break = true;
					}
				}
				if($break)  //break if phone number has been found
					break;
			}
				
			if(! is_numeric($phone_num))
				return "";
                    
			return $phone_num;
		}
		/**
			Extract and set the address, city, province, country, and postal code of the customer.
			@param	$location			location where client resides
			@param	$record_index	        index of client record
			@param	$count_inner_fields	counts number of client fields within table field
			@param	$field_index		index of table field
			@param	$length		        number of characters within $location
			@return	$count_inner_fields	counts number of client fields within table field
		*/
		function set_location($location, $record_index, $count_inner_fields, $field_index, $length)
		{
			global $client_info;
                
			$index_final_comma = get_last_comma_index($location, $length);
			
			$address_city = substr($location, 0, $index_final_comma);   //get client's address and city
			$client_info[$record_index][$field_index+$count_inner_fields++] = $address_city;
			
			$province_postcode_country = substr($location, $index_final_comma + 2);     //get province, postal code, and country
			list($province, $postcode_country) = split(' ', $province_postcode_country, 2);     //get province, postal code and country
			$client_info[$record_index][$field_index+$count_inner_fields++] = $province;
			
			$postal_code = substr($postcode_country, 0, 7);     //get postal code
			$client_info[$record_index][$field_index+$count_inner_fields++] = $postal_code;
			
			$country = substr($location, strlen($location)-3, 3);   //get country
			$client_info[$record_index][$field_index+$count_inner_fields] = $country;
	
			return $count_inner_fields;
		}
		/**
			Get the index of the last comma in location.
			@param	$location       location where client resides
			@param	$length	number of characters withint $location
			@return	$jj		index of last comma in location
		*/
		function get_last_comma_index($location, $length)
		{
			$jj;
			for($jj = $length - 1; $jj >= 0; $jj--)
				if( strrpos($location, ',', $jj) )
					break;
				
			return $jj;
		}
		/**
			Set the name of user entering client information and the date and time entered. 
			@param	$user_time               	 name of user entering record and date and time entered
			@param	$record_index		index of client record
			@param	$count_inner_fields	counts client fields within table field
			@param	$count_fields		counts fields within table
			@param	$length			number of characters within $user_time
			@return	$count_inner_fields	counts client fields within table field
		*/
		function set_user_time($user_time, $record_index, $count_inner_fields, $count_fields, $length)
		{
			global $client_info;
	
			$date = "";
			$time = "";
			
			$first_date_index = get_date_index($user_time, $length);
			$user = substr($user_time, 0, $first_date_index);   //get user entering record
			$client_info[$record_index][$count_fields+$count_inner_fields++] = $user; //initialize user
			//echo "index of user " . $count_inner_fields."<br>";
				
			if($first_date_index < $length)     //check that time has been entered
			{
				$date_and_time = substr($user_time, $first_date_index); //get date and time record entered
				list($date, $time) = split(' ', $date_and_time, 2);
				$date = get_date($date, $length);
				//echo $date . " " . $time . "<br>";
			}
			
			$client_info[$record_index][$count_fields+ ($count_inner_fields++)] = $date;   //set date
			$client_info[$record_index][$count_fields+$count_inner_fields] = $time;

			return $count_inner_fields;
		}
		/**
			Get first index of time customer was entered in form.
			@param	$user_time                name of user entering record and date and time entered
			@param	$length		        number of characters within $user_time
			@return	$first_digit_index	first index of date and time client entered
		*/
		function get_date_index($user_time, $length)
		{
			$first_digit_index;   //store index of first character in time entered			
			for($first_digit_index = 0; $first_digit_index < $length; $first_digit_index++) //find index of time entered
				if( is_numeric( substr($user_time, $first_digit_index, 1) ) )
					break;
		
			return $first_digit_index;
		}
           
		?>

	</body>
</html>