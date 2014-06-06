CREATE TABLE IF NOT EXISTS User (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS Movie (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS Rating (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `rating` INT(11) NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));
  
CREATE TABLE IF NOT EXISTS User_Movie (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `movie_id` INT(11) NOT NULL,
  `rating` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_User_Movie_User_idx` (`user_id` ASC),
  INDEX `fk_User_Movie_Movie1_idx` (`movie_id` ASC),
  CONSTRAINT `fk_User_Movie_User`
    FOREIGN KEY (`user_id`)
    REFERENCES `User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_Movie_Movie`
    FOREIGN KEY (`movie_id`)
    REFERENCES `Movie` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS Similar_User 
(
  `userid` INT(11) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `similaruserid` INT(11) NOT NULL,
  `similarusername` VARCHAR(45) NOT NULL,
  `pearsoncoeff` DOUBLE NOT NULL,
  PRIMARY KEY (`similaruserid`));

CREATE TABLE IF NOT EXISTS SimilarUser_Movies
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `similaruserid` INT(11) NOT NULL,
  `similarusername` VARCHAR(45) NOT NULL,
  `similarusermovieid` INT(11) NOT NULL,
  `similarusermovietitle` VARCHAR(45) NOT NULL,
  `similaruserrating` DOUBLE NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS Recommended_Movies 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `userid` INT(11) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `movieid` INT(11) NOT NULL, 
  `movietitle` VARCHAR(45) NOT NULL,
  `predicteduserrating` DOUBLE NOT NULL,
  PRIMARY KEY (`id`));