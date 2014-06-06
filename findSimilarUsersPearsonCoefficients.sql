DROP TABLE IF EXISTS Similar_User;
CREATE TABLE IF NOT EXISTS Similar_User 
(
  `userid` INT(11) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `similaruserid` INT(11) NOT NULL,
  `similarusername` VARCHAR(45) NOT NULL,
  `pearsoncoeff` DOUBLE NOT NULL,
  PRIMARY KEY (`similaruserid`)
);
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
	where umrt1.userid =8 and umrt2.userid!=8 
	GROUP BY umrt1.userid, umrt2.userid

) pearson
ORDER BY
pearsoncoeff DESC

