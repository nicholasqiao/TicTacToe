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
	,tie int not null default 0
    ,created timestamp not null default current_timestamp
	);

/* current games table - keeps track of game status so that clients can synchronize states */
drop table if exists current_games;
create table current_games
	(gid int primary key auto_increment /* unique game identifier */
    ,uid_one int
    ,uid_two int
    ,game_state varchar(100)
	,turn int
	,winner int default null
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
	(uid int
	,achievement_id int
	,txt varchar(500) /* description of the achievment */
	,created timestamp default current_timestamp()
	);
/* combinations of achievement_id and uid are unique */
create unique index active_reqs_users on active_reqs (uid,achievement_id);


drop table if exists active_reqs;
create table active_reqs
	(req_id int primary key auto_increment
	,requester int not null /* a uid */
	,requested int not null /* reqeusted uid */
	,req_time timestamp default current_timestamp()
    );
/* make combinations of (requester,requested) unique */
create unique index active_reqs_users on active_reqs (requester,requested);


drop table if exists queue;
create table queue
	(entry_id int primary key auto_increment
	,uid int not null /* uid of somebody currently in queue looking for a match */
	,entered timestamp default current_timestamp
	);
