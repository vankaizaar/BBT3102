# BBT3102
Lab Work

## SQL Setup
Create database

```mysql
CREATE DATABASE btc3205;
```

Create table to hold users

```mysql
CREATE TABLE btc3205.user(id int AUTO_INCREMENT PRIMARY KEY, first_name varchar(32) NOT NULL, last_name varchar(32), user_city varchar(32));
```

Alter table to allow storage of username and password

```mysql
ALTER TABLE user ADD(username varchar(20),password text); 
```

Alter database to allow storage of files and reference them to users

```mysql
CREATE TABLE btc3205.uploads(
    id int AUTO_INCREMENT PRIMARY KEY,
    file_name varchar(255) NOT NULL,
    user_id INT NOT NULL, 
    file_size INT NOT NULL,
    INDEX user_index (user_id),
    FOREIGN KEY (user_id)
        REFERENCES btc3205.user(id)
        ON DELETE CASCADE
);
```
