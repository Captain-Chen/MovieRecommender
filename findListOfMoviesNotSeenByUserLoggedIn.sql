DROP TABLE IF EXISTS SimilarUser_Movies;
CREATE TABLE IF NOT EXISTS SimilarUser_Movies
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `similaruserid` INT(11) NOT NULL,
  `similarusername` VARCHAR(45) NOT NULL,
  `similarusermovieid` INT(11) NOT NULL,
  `similarusermovietitle` VARCHAR(45) NOT NULL,
  `similaruserrating` DOUBLE NOT NULL,
  PRIMARY KEY (`id`)
);
INSERT INTO SimilarUser_Movies(similaruserid, similarusername, similarusermovieid, similarusermovietitle, similaruserrating) 
SELECT 
simuser.userid as similaruserid, simuser.username as similarusername, simuser.similarusermovieid, simuser.similarusermovietitle, simuser.rating as similaruserrating 
from

(SELECT u.id as userid, u.name as username, m.id as similarusermovieid, m.title as similarusermovietitle, um.rating as rating 
					FROM User_Movie um
					inner join User u on u.id = um.user_id
					inner join Movie m on m.id = um.movie_id
					where um.user_id in ('10', '11')) simuser
					
left join 	
(SELECT u.id as userid, u.name as username, m.id as movieid, m.title as movietitle, um.rating as rating 
					FROM User_Movie um
					inner join User u on u.id = um.user_id
					inner join Movie m on m.id = um.movie_id
					where um.user_id = 8) user

on user.movieid = simuser.similarusermovieid
where user.userid is null
