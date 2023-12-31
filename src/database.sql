-- Create Database 
DROP DATABASE IF EXISTS apicurriculos;
CREATE DATABASE apicurriculos CHARACTER SET utf8 COLLATE utf8_general_ci;

USE apicurriculos;

-- Create table Users
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(256) NOT NULL,
    email VARCHAR(256) NOT NULL UNIQUE,
    pass VARCHAR(128) NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    registered DATETIME NOT NULL DEFAULT NOW(),

    PRIMARY KEY (id)
);

-- Create table Enterprises
CREATE TABLE enterprises (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(256) NOT NULL,
    email VARCHAR(256) NOT NULL UNIQUE,
    pass VARCHAR(128) NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    registered DATETIME NOT NULL DEFAULT NOW(),

    PRIMARY KEY (id)
);