<?php
ob_start();
session_start();

/*CHECK ALL THE ERROR CASES*/

?>


<!DOCTYPE html>
<html lang=en>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Udemy Automation Course Companion Site</title>
<link rel="stylesheet" href="css/main.css" media="screen" type="text/css" />

<style type="text/css" media="screen">

div.required {font-weight: 800;}
li#p_error {color: #CD4F39; width: 80%; padding-left: 0.5em; display: none;}
label {float: left; width: 115px; text-align: right; padding-right: 2px;}
div {padding-top: 2px;}
input {margin-bottom: 5px;}
div.note {color:black; font-size:.85em;}
#un_msg {display: none;}
#pwd_msg {display: none;}
#un_info:hover + #un_msg {display: inline;}
#pwd_info:hover + #pwd_msg {display: inline;}
.input {font-weight:bold;}
.texterror {font-weight:bold; color:red; font-size:1.15em;}
.error {background:yellow;}
h2#heading {margin-left: 100px;}
h3#login {margin-left: 100px;}

</style>

<script type="text/javascript" language="javascript">
<!--
/*variables*/

var fName;
var lName;
var username;
var password;
var confirmPW;
var month;
var day;
var year;
var gender;
var age;

/*function to do all validation*/
function validate(){

	
	/*variable to keep track of number of errors*/
	var counter = 0;
	/*variable to hold error messages*/
	var strMsg = "<strong>The following errors must be corrected before the form is to be submitted:</strong><br /><br />";
	var p_error_handle = document.getElementById("p_error");
	

	if(isEmpty(getFirstName())){
		strMsg += "<li>Enter first name.</li>";
		document.getElementById("fname").style.background = "yellow";
		counter = 1;
	}
	else if(!isLetter(fName)){
		strMsg += "<li>First name must contain only letters.</li>";
 		document.getElementById("fname").style.background = "yellow";
		counter = 1;
	}		
	if(isEmpty(getLastName())){
		strMsg += "<li>Enter last name.</li>";
		document.getElementById("lname").style.background = "yellow";
		if (counter == 0)
			counter = 2;
	}
	else if(!isLetter(lName)){
		strMsg += "<li>Last name must contain only letters.</li>";
		document.getElementById("lname").style.background = "yellow";
		if (counter == 0)
			counter = 2;
	}	
	if(isEmpty(getUsername())){
		strMsg += "<li>Enter username.</li>";
		document.getElementById("username").style.background = "yellow";
		counter = 3;
	}			
	if(isEmpty(getPassword())){
		strMsg += "<li>Enter password.</li>";
		document.getElementById("password").style.background = "yellow";
		counter = 4;
	}	
	else if ((password.length > 20) && (password.length < 6)){
		strMsg += "<li>Password must have 6 to 20 characters.</li>";
		document.getElementById("password").style.background = "yellow";
		counter = 4;
	}
	if(isEmpty(getConfirmPW())){
		strMsg += "<li>Confirm password.</li>";
		document.getElementById("confirmPW").style.background = "yellow";
		counter = 5;
	}			
	else if ((confirmPW.length > 20) && (confirmPW.length < 6)){
		strMsg += "<li>Password must have 6 to 20 characters.</li>";
		document.getElementById("confirmPW").style.background = "yellow";
		counter = 5;
	}	
	if(getPassword() != getConfirmPW()){
		strMsg += "<li>Password and Confirmation must match.</li>";
		document.getElementById("password").style.background = "yellow";
		document.getElementById("confirmPW").style.background = "yellow";
		counter = 4;
	}	

/*puts focus in correct input field*/
	switch (counter){
		case 5:
			document.getElementById("confirmPW").focus();
			break;
		case 4:
			document.getElementById("password").focus();
			break;
		case 3:
			document.getElementById("username").focus();
			break;	
		case 2:
			document.getElementById("lName").focus();
			break;	
		case 1:
			document.getElementById("fName").focus();
			break;	
	} /*end switch*/
		
/*checks to see if error occured; if none, brings user to submit page*/	
	if (counter > 0){
		/*displays error messages*/
		p_error_handle.style.display='block';
		p_error_handle.innerHTML = strMsg;
		return false;
	}
	else if (counter == 0)
		return true;			
} /*end validate*/		

/*get functions return the value that the user input*/

function getFirstName(){
	fName = document.getElementById("fname").value;
	return fName;
}	

function getLastName(){
	lName = document.getElementById("lname").value;
 	return lName;
}

function getUsername(){
	username = document.getElementById("username").value;
 	return username;
}

function getPassword(){
	password = document.getElementById("password").value;
 	return password;
}

function getConfirmPW(){
	confirmPW = document.getElementById("confirmPW").value;
 	return confirmPW;
}

function getBirthMonth(){
	month = document.getElementById("month").value;
 	return month;
}

function getBirthDay(){
	day = document.getElementById("day").value;
 	return day;
}

function getBirthYear(){
	year = document.getElementById("year").value;
 	return year;
}

function getGender(){
	gender = document.getElementById("gender").value;
 	return gender;
}



/*function to check for empty string*/
function isEmpty(string){
	return (string=="");
}

/*function to check that the inputted string contains only integers*/

function isInteger(input){
	//looks to find first character that is not a digit
	var pattern = /[^0-9]+/;
	//if such a character is found, will return true so we need the negation to be returned (true if it is an integer)
	return !pattern.test(input);
}

/*function to check that the inputted string contains only letters*/

function isLetter(input){
	//looks to find first character that is not a letter, space or dash
	var pattern = /[^a-zA-Z\s-]+/i;
	//if such a character is found, will return true so we need the negation to be returned (true if it is a letter)
	return !pattern.test(input);
}
-->
</script>

</head>

<body>
  
  
  
<div class="content">

<h2 id="heading">Register</h2>

  <?php
  $config_data = parse_ini_file("config.ini");

  if (isset($_POST)) {
  	if ((isset($_POST['submit']) && $_POST['submit'] == "Register")) {
		$result = error_check($_POST);
		if (count($result) == 0){

			$username = $_POST['username'];
      		$password = md5($_POST['password']);

			// add info to the database 
			// connect to db

			try {
				  $db_host = $config_data['db_host'];
		          $db = $config_data['db'];
          		  $connection = new PDO("mysql:host=$db_host;dbname=$db;charset=utf8", $config_data['db_user'], $config_data['db_pwd']);
		          $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		          $ps = $connection->prepare("INSERT INTO users (`username`, `password`, `create_date`) VALUES (:username, :password, :create_date)");
		          $ps->bindParam(':username', $username);
		          $ps->bindParam(':password', $password);
		          $date = date("Y-m-d H:i:s");
				  $ps->bindParam(':create_date', $date);
		          $ps->execute();
		    }
		    catch(PDOException $e) {
		          echo 'ERROR: ' . $e->getMessage();
		    }

		    $_SESSION['valid_user'] = $username;
		
			// direct user to main page
			$host  = $_SERVER['HTTP_HOST'];
        	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        	header("Location:http://$host$uri/main.php");
		}
		else {
			print_form($_POST, $result);
		}
	}
	else print_form(NULL, NULL);
  }	

  
  //functions
  
  function counter($list)
{
	$t =0;
	foreach($list as $var)
		if(!empty($var))
			$t++;
	return $t;
}	

/*error-checking function*/
function error_check($someList){

/*used to see if errors are present*/
$num_errors=0;

$error = array();

if ($someList['fname']==''){
	echo '<p class="texterror">Enter First Name.</p>';
	$error['fname'] = 1;
	$num_errors++;
}
if ($someList['lname']==''){
	echo '<p class="texterror">Enter Last Name.</p>';
	$error['lname'] = 1;
	$num_errors++;
}
if ($someList['username']==''){
	echo '<p class="texterror">Enter Username.</p>';
	$error['username'] = 1;
	$num_errors++;
}
if ((strlen($someList['username']) < 6) || (strlen($someList['username']) > 20)){
	echo '<p class="texterror">Username must be between 6 and 20 characters long.</p>';
	$error['username'] = 1;
	$num_errors++;
} 
if ($someList['password']==''){
	echo '<p class="texterror">Enter Password.</p>';
	$error['password'] = 1;
	$num_errors++;
}
if ((strlen($someList['password']) < 6) || (strlen($someList['password']) > 20)){
	echo '<p class="texterror">Password must be between 6 and 20 characters long.</p>';
	$error['password'] = 1;
	$num_errors++;
} 
if ($someList['confirmPW']==''){
	echo '<p class="texterror">Enter Password Confirmation.</p>';
	$error['confirmPW'] = 1;
	$num_errors++;	
}
if ($someList['password'] != $someList['confirmPW']){
	echo '<p class="texterror">Password and Confirmation must match.</p>';
	$error['password'] = 1;
	$error['confirmPW'] =1;
	$num_errors++;
}

	return $error;


} /*end function error_check*/	


function print_form($list,$error){



	print ('
	<ul><li id="p_error"></li></ul>
		<form method="post" action ="register.php">
<div class="input">First name:<br />');
if(!$error['fname'])
	print('<input type="text" name="fname" id="fname" size="60" value="'.$list['fname'].'" /></div>');
else
	print('<input type="text" class="error" name="fname" id="fname" size="60" value="'.$list['fname'].'" /></div>');
print('
<div class="input">Last name:<br />');
if(!$error['lname'])
	print('<input type="text" name="lname" id="lname" size="60" value="'.$list['lname'].'" /></div>');
else
	print('<input type="text" class="error" name="lname" id="lname" size="60" value="'.$list['lname'].'" /></div>');	
print ('
<span class="input">Username: <br />');
if(!$error['username'])
	print('<input type="text" name="username" id="username" size="20" value="'.$list['username'].'" /></span>');
else
	print('<input type="text" class="error" name="username" id="username" size="20" value="'.$list['username'].'" /></span>');
print('<span id="un_info">&#9432;</span><div class="note" id="un_msg">Username must be between 6 and 20 characters.</div><br />');		
print ('
<span class="input">Password: <br />');
if(!$error['password'])
	print('<input type="password" name="password" id="password" size="20" value="'.$list['password'].'"/></span>');
else
	print('<input type="password" class="error" name="password" id="password" size="20" value="'.$list['password'].'"/></span>');
print('<span id="pwd_info">&#9432;</span><div class="note" id="pwd_msg">Password must be between 6 and 20 characters.</div>');	
print('		
<div class="input">Confirm Password:<br />');
if(!$error['confirmPW'])
	print('<input type="password" name="confirmPW" id="confirmPW" size="20" value="'.$list['confirmPW'].'"/></div>');
else
	print('<input type="password" class="error" name="confirmPW" id="confirmPW" size="20" value="'.$list['confirmPW'].'"/></div>');	
print('		
<div class="input">Date of Birth (mm/dd/yyyy):<br />');
print ('
<select name="month" id="month">
');

	for ($startMonth = 1; $startMonth <= 12; $startMonth++)
	{
		$startMonth = sprintf('%02d', $startMonth);
		if(isset($_POST['month']) && $_POST['month']==$startMonth)
			print('<option value="'.$startMonth.'" selected="selected">'.$startMonth.'</option>');
		else
			print('<option value="'.$startMonth.'">'.$startMonth.'</option>');
	}
print ('	
</select>
');

print ('
<select name="day" id="day">');

	for($startDay = 1; $startDay <= 31; $startDay++)
	{
		$startDay = sprintf('%02d', $startDay);
		if(isset($_POST['day']) && $_POST['day']==$startDay)
			print('<option value="'.$startDay.'" selected="selected">'.$startDay.'</option>');
		else
			print('<option value="'.$startDay.'">'.$startDay.'</option>');
	}

print ('
</select>');

print('

	<select name="year" id="year">						
');
	for($startYear = date('Y'); $startYear >= 1915; $startYear--)
	{
		if(isset($_POST['year']) && $_POST['year']==$startYear)
			print('<option value="'.$startYear.'" selected="selected">'.$startYear.'</option>');
		else
			print('<option value="'.$startYear.'">'.$startYear.'</option>');
	}
	print('
	</select></div>');


print ('<div class="input">Gender<br />
<select name="gender" id="gender">');

/*use for gender*/
$gender =  array("Female","Male");

foreach ($gender as $key)
	{
		if(isset($_POST['gender']) && $_POST['gender']==$key)
			print('<option value="'.$key.'" selected="selected">'.$key.'</option>');
		else
			print('<option value="'.$key.'">'.$key.'</option>');	
	}
print ('</select></div>');		

print('	
<br /><br />

<input type="submit" name="submit" value="Register" id="submit" onclick="return validate();" />
</form>

<br /><br />		
		
<h3 id="login">Click <a href="login.php">here</a> to go back to log in page.</h3>		
		');
} //end print_form

?>
</div>
<!-- div.content -->



</body>
</html>