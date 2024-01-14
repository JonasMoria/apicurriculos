-- Create Database 
DROP DATABASE IF EXISTS apicurriculums;
CREATE DATABASE apicurriculums CHARACTER SET utf8 COLLATE utf8_general_ci;

USE apicurriculums;

-- Create table Users
DROP TABLE IF EXISTS `users`;
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
DROP TABLE IF EXISTS `enterprises`;
CREATE TABLE enterprises (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(256) NOT NULL,
    email VARCHAR(256) NOT NULL UNIQUE,
    pass VARCHAR(128) NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    registered DATETIME NOT NULL DEFAULT NOW(),

    PRIMARY KEY (id)
);

-- Create table curriculum
DROP TABLE IF EXISTS `users_curriculum`;
CREATE TABLE `users_curriculum` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT UNSIGNED NOT NULL,
    cv_name VARCHAR(256) NOT NULL,
    oficial TINYINT(1) NOT NULL DEFAULT 0,
    updated DATETIME NOT NULL DEFAULT NOW(),
    status TINYINT(1) NOT NULL DEFAULT 1,
    
    PRIMARY KEY(id),
    CONSTRAINT fk_curriculum_user_id
		FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create table personal informations
DROP TABLE IF EXISTS `users_personal_infos`;
CREATE TABLE `users_personal_infos` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	curriculum_id INT UNSIGNED NOT NULL,
    person_name VARCHAR(256) NOT NULL,
	person_email VARCHAR(256) NOT NULL,
    person_city VARCHAR(256) NOT NULL,
    person_uf VARCHAR(2) NOT NULL,
    person_birth DATE NOT NULL,
    person_desc VARCHAR(500),
    
	PRIMARY KEY(id),
    CONSTRAINT fk_info_curriculum
		FOREIGN KEY (curriculum_id) REFERENCES users_curriculum(id)
);

-- Create table personal contacts
DROP TABLE IF EXISTS `users_personal_contacts`;
CREATE TABLE `users_personal_contacts` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	curriculum_id INT UNSIGNED NOT NULL,
	person_phone_principal VARCHAR(13) NOT NULL,
	person_phone_secondary VARCHAR (12),
	person_linkedin VARCHAR(256),
	person_facebook VARCHAR(256),
	person_instragram VARCHAR(256),
	person_site VARCHAR(256),

	PRIMARY KEY(id),
		CONSTRAINT fk_contact_curriculum
		FOREIGN KEY (curriculum_id) REFERENCES users_curriculum(id)
);

-- Create table personal education
DROP TABLE IF EXISTS `users_personal_educations`;
CREATE TABLE `users_personal_educations` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	curriculum_id INT UNSIGNED NOT NULL,
	person_course_acronym VARCHAR(5),
    person_course_name VARCHAR(256),
	person_course_modality VARCHAR(64),
	person_course_institution VARCHAR(256),
	person_course_status INT(1),
	person_course_init DATE,
	person_course_finish DATE,

	PRIMARY KEY(id),
		CONSTRAINT fk_education_curriculum
		FOREIGN KEY (curriculum_id) REFERENCES users_curriculum(id)
);

-- Create table personal experiences
DROP TABLE IF EXISTS `users_personal_experiences`;
CREATE TABLE `users_personal_experiences` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	curriculum_id INT UNSIGNED NOT NULL,
	person_experience_enterprise VARCHAR(256),
    person_experience_office VARCHAR(256),
    person_experience_activities VARCHAR(512),
	person_experience_init DATE,
	person_experience_finish DATE,

	PRIMARY KEY(id),
		CONSTRAINT fk_experience_curriculum
		FOREIGN KEY (curriculum_id) REFERENCES users_curriculum(id)
);

-- Create table personal skills
DROP TABLE IF EXISTS `users_personal_skills`;
CREATE TABLE `users_personal_skills` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	curriculum_id INT UNSIGNED NOT NULL,
    person_skill_name VARCHAR(64),
    
	PRIMARY KEY(id),
		CONSTRAINT fk_skills_curriculum
		FOREIGN KEY (curriculum_id) REFERENCES users_curriculum(id)
);

-- Create table personal languages
DROP TABLE IF EXISTS `users_personal_langs`;
CREATE TABLE `users_personal_langs` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	curriculum_id INT UNSIGNED NOT NULL,
    person_lang_name VARCHAR(64),
    
	PRIMARY KEY(id),
		CONSTRAINT fk_langs_curriculum
		FOREIGN KEY (curriculum_id) REFERENCES users_curriculum(id)
);
