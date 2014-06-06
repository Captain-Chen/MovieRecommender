<?php
require("dbconfig.php");
session_start();
$current_id = $_SESSION['current_id']; // pass in current id
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<meta charset="utf-8">
<title>Movie Rater | Raw Data</title>
</head>
<body>
<h1>Raw Data</h1>
<?php

$sql = "SHOW Tables";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

echo '<table border="1">';
echo "<tr>";
for($i=0; $i<$fields_num; $i++){
	$field = mysql_fetch_field($result);
	echo "<th>TABLES</th>";
}
echo "</tr>\n";
while($row = mysql_fetch_row($result)){
	echo "<tr>";
	foreach($row AS $cell)
		echo "<td>$cell</td>";
	echo "</tr>\n";
}
echo '</table>';

echo "<h2>User Table</h2>";
$sql = "SELECT * FROM User";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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

echo "<h2>User_Movie Table</h2>";
$sql = <<< EOF
SELECT user_id, movie_id, rating FROM User_Movie
ORDER BY user_id ASC;
EOF;
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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

echo "<h2>Movies Table</h2>";
$sql = "SELECT * FROM Movie";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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
echo "</table>";

echo "<h2>Movies Rated by Current User</h2>";
$sql = "SELECT user_id,movie_id,rating FROM User_Movie WHERE user_id=$current_id";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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

echo "<h2>Similar_User Table</h2>";
$sql = "SELECT * FROM Similar_User";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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

echo "<h2>Users with Similar Tastes in Movie</h2>";
$sql = "SELECT * FROM SimilarUser_Movies";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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

echo "<h2>Recommended Movies</h2>";
$sql = "SELECT * FROM Recommended_Movies";
$result = mysql_query($sql);
$fields_num = mysql_num_fields($result);

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

// Close the connection
mysql_close($connection);
?>
<a href="home.php">Go back to home</a>
</body>
</html>