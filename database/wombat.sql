/* Tic Tac Toe main database script
 * Mike Gedelman
 * 
 * Run this .sql file as root to create the appropriate database objets for the TTT project.
 */

/* create a schema/database to hold all our stuff */
drop database if exists wombat;
create database wombat;
use wombat;

/* main user table that references everything else */
drop table if exists users;
create table users
	(uid int primary key auto_increment
	,username varchar(200) not null /* email serves as username */
    ,pass varchar(40) not null /* php function sha1() returns 40-character hash */
    ,win int not null default 0
	,loss int not null default 0
    ,created timestamp not null default current_timestamp
	);

/* current games table - keeps track of game status so that clients can synchronize states */
drop table if exists current_games;
create table current_games
	(gid int primary key auto_increment /* unique game identifier */
    ,uid_one int
    ,uid_two int
    ,game_state varchar(100)
    /* ,game_status varchar(10) /* maybe something like PROG for in progress, OVER for over */
    );

/* friend list table. simple mapping between two users.
 * assumes one-way mapping (allows for unreciprocated friends)
 */
drop table if exists friendlist;
create table friendlist
	(entry_id int primary key auto_increment
     ,uid int
     ,friend int
	);

drop table if exists achievements;
create table achievements
	(entry_id int primary key auto_increment
	,uid int
	,txt varchar(500) /* description of the achievment */
	);
drop table if exists active_reqs;
create table active_reqs
	(req_id int primary key auto_increment
	,reqeuster int /* a uid */
	,requested int /* reqeusted uid */
    );

drop table if exists queue;
create table queue
	(entry_id int primary key auto_increment
	,uid int /* uid of somebody currently in queue looking for a match */
	,entered timestamp default current_timestamp
	);