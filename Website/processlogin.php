<?php
// sql connection stuff
require("dbconfig.php");

// username and password from form
$username=$_POST['username'];
$password=$_POST['password'];

// To protect MySQL injection
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$encrypted_password=md5($password);

// sql query
$sql="SELECT * FROM $tbl_name WHERE name='$username' AND password='$encrypted_password'";
$result=mysql_query($sql) or die(mysql_error());

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){

// Register $username, $password and redirect to file "login_success.php"
session_start();
$_SESSION['username'] = $username;
$_SESSION['password'] = $encrypted_password;
mysql_close($connection);
header("location:home.php");
} else {
echo "Wrong Username or Password";
mysql_close($connection);
}
//ob_end_flush();
?>