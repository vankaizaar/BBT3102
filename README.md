# BBT3102
Lab Work

## SQL Setup
Create database

```CREATE DATABASE btc3205;```

Create table to hold users

``` CREATE TABLE btc3205.user(
    id int AUTO_INCREMENT PRIMARY KEY, 
    first_name varchar(32) NOT NULL,
    last_name varchar(32),
    user_city varchar(32)); 

Alter table

``` ALTER TABLE user ADD(username varchar(20),password text); ```
