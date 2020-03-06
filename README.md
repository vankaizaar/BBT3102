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

Alter database to allow storage of UTC + timestamp

```mysql
ALTER TABLE user ADD(utc_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,tzoffset INT NOT NULL); 
```

Alter database to allow storage of API Keys

```mysql
CREATE TABLE btc3205.api_keys(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    api_key varchar(255) NOT NULL,
    INDEX user_index (user_id),
    FOREIGN KEY (user_id) REFERENCES btc3205.user(id)
    ON DELETE CASCADE
);
```

Alter database to allow storage of Orders

```mysql
CREATE TABLE btc3205.orders(
order_id int AUTO_INCREMENT PRIMARY KEY,
order_name varchar(255) NOT NULL,
units int,
unit_price double(3,2),
order_status varchar(32));
```
