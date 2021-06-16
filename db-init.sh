#!/bin/bash
service mysql start
mysql -u root -e "CREATE USER 'dbuser'@'%' IDENTIFIED BY 'password';"
mysql -u root -e "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, DROP, REFERENCES, PROCESS, SHOW VIEW ON *.* TO 'dbuser'@'%';"
mysql -u root -e "CREATE DATABASE cms;"