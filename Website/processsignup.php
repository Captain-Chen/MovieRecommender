<?php
// sql connection stuff
require("dbconfig.php");

// values sent from form 
$username=$_POST['username'];
$password=$_POST['password'];
$encrypted_password=md5($password);

// To protect MySQL injection
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$encrypted_password=md5($password);

// Check if record exists in User database
$sql="SELECT * FROM User WHERE name='$username'";
$result=mysql_query($sql) or die(mysql_error());
$count=mysql_num_rows($result);

// If result matched $myusername there must be a record already
if($count==1){
echo("Account already exists<br>");
echo("<a href=\"signup.php\">Back to Sign-up page</a>");
mysql_close($connection);
die(mysql_error());
}else{
// Insert new record into database 
$sql="INSERT INTO $tbl_name(name, password) VALUES('$username', '$encrypted_password')";
$result=mysql_query($sql) or die(mysql_error());
}
	
if($result){
echo "<h1>You have successfully been registered!</h1>";
} else {
echo "Registration failed!";
echo $result;
}
mysql_close($connection);
echo "<a href=\"index.html\">Go Back To The Login Page</a>";
?>
