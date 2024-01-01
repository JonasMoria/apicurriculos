<?php

namespace App\Models;

use App\DAO\CurriculumDAO;
use DateTime;
use InvalidArgumentException;

class CurriculumModel {
    private string $personID;
    private string $personName;
    private string $personEmail;
    private string $personCity;
    private string $personUF;
    private DateTime $personBirthDate;

    private array $personPhones;
    private array $personSocialNetworks;
    private array $personEducation;
    private array $personSkills;
    private array $personLanguages;
    private array $personExperiences;

    private $DAO;

    public function __construct() {
        $this->DAO = new CurriculumDAO();
    }

    public function setPersonID(string $personID) {
        if (empty($personID) || $personID < 0) {
            throw new InvalidArgumentException('Campo ID do usuário obrigatório');
        }

        $this->personID = $personID;
    }

    public function setPersonName(string $personName) {
        Security::validadePersonName($personName);

        $this->personName = $personName;
    }

    public function setPersonEmail(string $personEmail) {
        Security::validateEmail($personEmail);

        $this->personEmail = $personEmail;
    }

    public function setPersonCity(string $personCity) {
        Security::validadeCity($personCity);

        $this->personCity = $personCity;
    }

    public function setPersonUF(string $personUF) {
        Security::validateUF($personUF);

        $this->personUF = $personUF;
    }

    public function setPersonPhones (array $personPhones) {
        $this->personPhones = $personPhones;
    }

    public function setPersonSocialNetworks (array $personSocialNetworks) {
        $this->personSocialNetworks = $personSocialNetworks;
    }

    public function setPersonEducation (array $personEducation) {
        $this-> personEducation= $personEducation;
    }

    public function setPersonSkills (array $personSkills) {
        $this->personSkills = $personSkills;
    }

    public function setPersonLanguages (array $personLanguages) {
        $this->personLanguages = $personLanguages;
    }

    public function setPersonExperiences (array $personExperiences) {
        $this->personExperiences = $personExperiences;
    }


    public function setPersonBirthDate(DateTime $personBirthDate) {
        Security::validateDate($personBirthDate);

        $this->personBirthDate = $personBirthDate;
    }

    public function getPersonID() {
        return $this->personID;
    }

    public function getPersonName() {
        return $this->personName;
    }

    public function getPersonEmail() {
        return $this->personEmail;
    }

    public function getPersonCity() {
        return $this->personCity;
    }

    public function getPersonUF() {
        return $this->personUF;
    }

    public function getPersonBirthDate() {
        return $this->personBirthDate;
    }

    public function getPersonPhones() {
        return $this->personPhones;
    }

    public function getPersonSocialNetworks() {
        return $this->personSocialNetworks;
    }

    public function getPersonEducation() {
        return $this->personEducation;
    }

    public function getPersonSkills() {
        return $this->personSkills;
    }

    public function getPersonLanguages() {
        return $this->personLanguages;
    }

    public function getPersonExperiences() {
        return $this->personExperiences;
    }
}