<?php 
require("dbconfig.php");
// check if user's session is registered, if not will redirect them back to login
session_start();
if(!isset($_SESSION['username'])){ 
header("location:index.html"); // redirect back to login page
}
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<meta charset="utf-8">
<title>Movie Rater | Rate a Movie</title>
</head>

<body>
<h1>Rate a Movie</h1>
<img src="img/neko4.png" alt="indecisive cat">
<form action="display.php" method="post">
Movie: <select name="title">
<?php
$sql = <<< EOF
SELECT * FROM Movie
ORDER BY title ASC
EOF;
$result = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($result)){
//value=movie_id, title
echo "<option value=\"$row[id]\">" . $row['title'] . "</option>\n";
}
?>
</select><br>
Rating: <select name="rating">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
</select><br>
<input type="submit" value="Submit">
</form>
<a href="home.php">Back to home</a>
</body>
</html>
