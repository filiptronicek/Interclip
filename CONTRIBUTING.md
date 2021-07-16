# Setting up Interclip locally
Hi, thanks for considering contributing to Interclip! Here's a guide on how to get you set up.

## The quick and easy way - [GitPod](https://www.gitpod.io/)
[![Open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#https://github.com/aperta-principium/Interclip)


## Prerequisites
- Apache server (needed for the .htaccess redirections)
- PHP 7.4+
- MySQL database server
- Composer v2 or newer
- Git
- Sass CLI
- (optional) Redis ([installation guide](https://redis.io/download))

## Installing dependencies
1. `composer install`
2. (optional) Install the Redis PHP extension: `sudo apt-get install php-redis`
3. (optional) After downloading the extension, add this to your php.ini: `extension=redis.so` (you may need to restart your Apache server afterwards)

## Compiling stylesheets
1. Install sass globally: `npm i -g sass`
2. Watch the styles `sass scss/:css/ -w -s compressed`

## Setting up the env files
1. First things first, copy the example .env file called `.env.sample`:
```
cp .env.sample .env
```
2. Change the values to your environment, most importantly
    * DB credentials, like the server, username, and password of your MySQL database
    * The environment, like staging, development, or production
    * The cryptographic salt used for hashing IP addresses
    * Auth0 credentials (optional)
    * Sentry credentials (optional)
    * Rclone config

## Setting up the database
You can create the database and the tables by just executing the following SQL query:
```sql

USE iclip;

/* Rate limit table */
DROP TABLE IF EXISTS `hits`;
CREATE TABLE `hits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iphash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `operation` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Clips table */
DROP TABLE IF EXISTS `userurl`;
CREATE TABLE `userurl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usr` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Accounts table */
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `email` varchar(128) NOT NULL,
  `role` varchar(64) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0;

/* Add the mocked admin account as an admin on the site */
INSERT INTO accounts VALUES('admin@example.org', 'staff', NULL);

``` 

### Setting up MySQL cron jobs
```sql
/* Delete expired clips (runs every hour) */
CREATE EVENT `clean_expired` ON SCHEDULE EVERY 1 HOUR STARTS '2021-02-01 13:39:14' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM userurl WHERE expires < CURDATE();

/* Cleans the DB hits table with events older than 5 hours */ 
CREATE EVENT `clean_hits` ON SCHEDULE EVERY 1 DAY STARTS '2014-01-18 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `hits` where `date` < (CURRENT_TIMESTAMP - 18000);
```
