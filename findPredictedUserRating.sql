DROP TABLE IF EXISTS Recommended_Movies;
CREATE TABLE IF NOT EXISTS Recommended_Movies 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `userid` INT(11) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `movieid` INT(11) NOT NULL, 
  `movietitle` VARCHAR(45) NOT NULL,
  `predicteduserrating` DOUBLE NOT NULL,
  PRIMARY KEY (`id`));

INSERT INTO Recommended_Movies(userid, username, movieid, movietitle, predicteduserrating)
SELECT 
userid, username, movieid, movietitle, ROUND((1/SUM(simusermovies.pearsoncoeff))*(SUM(simusermovies.pearsoncoeff*simusermovies.similaruserrating)), 2) as predicteduserrating
from

(SELECT su.userid, su.username, sumovies.similaruserid, sumovies.similarusername, sumovies.similarusermovieid as movieid, sumovies.similarusermovietitle as movietitle, sumovies.similaruserrating, su.pearsoncoeff 
FROM SimilarUser_Movies sumovies
inner join Similar_User su
on sumovies.similaruserid = su.similaruserid
) simusermovies

