<?php
require("dbconfig.php");
session_start();
// Session data of current user
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Get Current User ID
$sql = "SELECT id FROM User WHERE name='$username' AND password='$password'";
$user_result = mysql_query($sql) or die(mysql_error());
$current_id = mysql_fetch_array($user_result);

$_SESSION['current_id'] = $current_id[0];

// information passed in from rate form (which movie_id and rating)
$movie_id = $_POST['title'];
$rating = $_POST['rating'];

// get current movie title
$sql = "SELECT title FROM Movie WHERE id='$movie_id'";
$movie_result = mysql_query($sql) or die(mysql_error());
$current_movie = mysql_fetch_array($movie_result);

echo <<< EOF
User-ID: $current_id[0]<br>
User: $username<br>
Movie-ID: $movie_id<br>
Movie Title: $current_movie[0]<br>
Rating: $rating<br>
EOF;

// CHECK IF RECORD EXISTS
$sql = "SELECT * FROM User_Movie WHERE user_id=$current_id[0] AND movie_id=$movie_id";
$result = mysql_query($sql) or die(mysql_error());
$count = mysql_num_rows($result);

if($count==1){
// UPDATE THE RECORD
$sql = "UPDATE User_Movie SET rating=$rating WHERE user_id=$current_id[0] AND movie_id=$movie_id";
mysql_query($sql);
}else{
// INSERT NEW RECORD
$sql = "INSERT INTO User_Movie(user_id, movie_id, rating) VALUES($current_id[0], $movie_id, $rating)";
mysql_query($sql);
}

// DROP TABLE IF IT EXISTS
$sql = "DROP TABLE IF EXISTS Similar_User";
mysql_query($sql);

// CREATE TABLE Similar_User IF IT DOES NOT EXIST
$sql = <<< EOF
CREATE TABLE IF NOT EXISTS Similar_User 
(
  `userid` INT(11) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `similaruserid` INT(11) NOT NULL,
  `similarusername` VARCHAR(45) NOT NULL,
  `pearsoncoeff` DOUBLE NOT NULL,
  PRIMARY KEY (`similaruserid`)
);
EOF;
mysql_query($sql);

// PEARSON COEFFICIENT CALCULATION
$sql = <<< EOF
INSERT INTO Similar_User(userid, username, similaruserid, similarusername, pearsoncoeff)
SELECT
userid, username, similaruserid, similarusername,
((psum - (sum1 * sum2 / n)) / sqrt((sum1sq - pow(sum1, 2.0) / n) * (sum2sq - pow(sum2, 2.0) / n))) AS pearsoncoeff
from

(
	select umrt1.userid as userid , umrt1.username as username, umrt2.userid as similaruserid, umrt2.username as similarusername, 
	SUM(umrt1.rating) AS sum1, 
	SUM(umrt2.rating) AS sum2,
	SUM(umrt1.rating * umrt1.rating) AS sum1sq,
	SUM(umrt2.rating * umrt2.rating) AS sum2sq,
	SUM(umrt1.rating * umrt2.rating) AS psum,
	COUNT(*) as n

	from

		(SELECT u.id as userid, u.name as username, m.title as movie, um.rating as rating 
					FROM User_Movie um
					inner join User u on u.id = um.user_id
					inner join Movie m on m.id = um.movie_id) as umrt1 
	left join

		(SELECT u.id as userid, u.name as username, m.title as movie, um.rating as rating 
					FROM User_Movie um
					inner join User u on u.id = um.user_id
					inner join Movie m on m.id = um.movie_id) as umrt2

	on umrt1.movie = umrt2.movie
	where umrt1.userid = $current_id[0] and umrt2.userid != $current_id[0] 
	GROUP BY umrt1.userid, umrt2.userid

) pearson
ORDER BY
pearsoncoeff DESC
EOF;
mysql_query($sql);

// DROP TABLE IF EXISTS SimilarUser_Movies;
$sql = "DROP TABLE IF EXISTS SimilarUser_Movies";
mysql_query($sql);

// CREATE TABLE IF NOT EXISTS SimilarUser_Movies
$sql = <<< EOF
CREATE TABLE IF NOT EXISTS SimilarUser_Movies
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `similaruserid` INT(11) NOT NULL,
  `similarusername` VARCHAR(45) NOT NULL,
  `similarusermovieid` INT(11) NOT NULL,
  `similarusermovietitle` VARCHAR(45) NOT NULL,
  `similaruserrating` DOUBLE NOT NULL,
  PRIMARY KEY (`id`));
EOF;
mysql_query($sql);

// Get Users from Similar_User table whose score is higher than 0
$sql = "SELECT similaruserid FROM Similar_User WHERE pearsoncoeff > 0";
$result = mysql_query($sql);
$sim_users = array();
while ($row_user = mysql_fetch_array($result)){
	$sim_users[] = $row_user[0];
}
$ids = join(',',$sim_users);
echo "Similar User IDs: $ids<br>";

// INSERT INTO SimilarUser_Movies Table
$sql = <<< EOF
INSERT INTO SimilarUser_Movies(similaruserid, similarusername, similarusermovieid, similarusermovietitle, similaruserrating) 
SELECT 
simuser.userid as similaruserid, simuser.username as similarusername, simuser.similarusermovieid, simuser.similarusermovietitle, simuser.rating as similaruserrating 
from

(SELECT u.id as userid, u.name as username, m.id as similarusermovieid, m.title as similarusermovietitle, um.rating as rating 
					FROM User_Movie um
					inner join User u on u.id = um.user_id
					inner join Movie m on m.id = um.movie_id
					where um.user_id in ($ids)) simuser
					
left join 	
(SELECT u.id as userid, u.name as username, m.id as movieid, m.title as movietitle, um.rating as rating 
					FROM User_Movie um
					inner join User u on u.id = um.user_id
					inner join Movie m on m.id = um.movie_id
					where um.user_id = $current_id[0]) user

on user.movieid = simuser.similarusermovieid
where user.userid is null
EOF;
mysql_query($sql);

// DROP TABLE If Recommended_Movies exists
$sql = "DROP TABLE IF EXISTS Recommended_Movies";
mysql_query($sql);

// CREATE TABLE IF NOT EXISTS Recommended_Movies
$sql = <<< EOF
CREATE TABLE IF NOT EXISTS Recommended_Movies 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `userid` INT(11) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `movieid` INT(11) NOT NULL, 
  `movietitle` VARCHAR(45) NOT NULL,
  `predicteduserrating` DOUBLE NOT NULL,
  PRIMARY KEY (`id`));
EOF;
mysql_query($sql);

// INSERT RESULT INTO Recommended_Movies table
$sql = <<< EOF
INSERT INTO Recommended_Movies(userid, username, movieid, movietitle, predicteduserrating)
SELECT 
userid, username, movieid, movietitle, ROUND((1/SUM(simusermovies.pearsoncoeff))*(SUM(simusermovies.pearsoncoeff*simusermovies.similaruserrating)), 2) as predicteduserrating
from

(SELECT su.userid, su.username, sumovies.similaruserid, sumovies.similarusername, sumovies.similarusermovieid as movieid, sumovies.similarusermovietitle as movietitle, sumovies.similaruserrating, su.pearsoncoeff 
FROM SimilarUser_Movies sumovies
inner join Similar_User su
on sumovies.similaruserid = su.similaruserid
) simusermovies
EOF;
mysql_query($sql);

// Close the connection
mysql_close($connection);
echo '<a href="detail.php">View Raw Data</a><br>';
echo '<a href="home.php">Go back to home page</a>';
?>