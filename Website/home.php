<?php
// check if user is registered, if not will redirect them back to login
session_start();
if(!isset($_SESSION['username'])){ 
header("location:index.html"); // redirect back to login page
}

// dbconfig.php
$db_host="localhost"; // Host name 
$db_username="sundarmu_mr"; // Mysql username 
$db_password="P3ssw0rd"; // Mysql password 
$db_name="sundarmu_movie_recommender"; // Database name 

// Connect to server and select databse.
$connection = mysql_connect($db_host,$db_username,$db_password) or die(mysql_error()); 
mysql_select_db("$db_name")or die("cannot select DB");
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<meta charset="utf-8">
<title>Movie Rater | Home</title>
</head>
<body>
<h1>Welcome to Movie Rater!</h1>
<img src="img/neko2.png" alt="a cat describing how great movies are">
<table border="1"> 
	<tr>
		<th colspan="2">Navigation</th>
	</tr>
	<tr>
		<td><a href="rate.php">Rate Movies</a></td>
		<td><a href="logout.php">Logout</a></td>
	</tr>
</table>
<?php
$username = $_SESSION['username'];

// Get Current User ID from DB
$sql = "SELECT id FROM User WHERE name='$username'";
$user_result = mysql_query($sql) or die(mysql_error());
$current_id = mysql_fetch_array($user_result);

$sql = <<< EOF
select umrt.* from 
(SELECT u.id as userid, u.name as username, m.id as movieid, m.title as movie, um.rating as rating 
     FROM User_Movie um
     inner join User u on u.id = um.user_id
     inner join Movie m on m.id = um.movie_id
where u.id = $current_id[0]
) umrt
ORDER BY umrt.username ASC
EOF;
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

echo "<h2>Movies you've rated</h2>";
echo '<table border="1">';
echo "<tr>";
for($i=0; $i<$fields_num; $i++){
	$field = mysql_fetch_field($result);
	echo "<th>{$field->name}</th>";
}
echo "</tr>\n";
while($row = mysql_fetch_row($result)){
	echo "<tr>";
	foreach($row AS $cell)
		echo "<td>$cell</td>";
	echo "</tr>\n";
}
echo '</table>';

echo "<h2>Movies recommended to you:</h2>";
// need to get movie title and predicted rating
$sql = "SELECT movietitle,predicteduserrating FROM Recommended_Movies WHERE username='$username'";
$result = mysql_query($sql) or die(mysql_error());
$count = mysql_num_rows($result);

if($count >= 1){
while($row = mysql_fetch_row($result)){
	for($i=0; $i < mysql_num_fields($result);$i++)
		echo $row[$i] . " ";
}
}else{
echo "<p>No recommendations</p>";
}

mysql_close($connection);
?>
</body>
</html>