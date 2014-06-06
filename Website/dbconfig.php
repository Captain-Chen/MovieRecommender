<?php
	$db_host="localhost"; // Host name 
	$db_username="sundarmu_mr"; // Mysql username 
	$db_password="P3ssw0rd"; // Mysql password 
	$db_name="sundarmu_movie_recommender"; // Database name 
	$tbl_name="User"; // Table name 

	// Connect to server and select databse.
	$connection = mysql_connect($db_host,$db_username,$db_password) or die(mysql_error()); 
	mysql_select_db("$db_name")or die("cannot select DB");
?>