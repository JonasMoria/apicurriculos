<?php

namespace App\Models;

use App\DAO\CurriculumDAO;
use DateTime;
use InvalidArgumentException;

class CurriculumModel {
    private string $personID;
    private string $curriculumName;
    private string $personName;
    private string $personEmail;
    private string $personCity;
    private string $personUF;
    private DateTime $personBirthDate;
    private string $personDescription;

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
        if ($personID < 0) {
            throw new InvalidArgumentException('Campo ID do usuário obrigatório');
        }

        $this->personID = $personID;
    }

    public function setCurriculumName(string $curriculumName) {
        Security::validateName($curriculumName);

        $this->curriculumName = $curriculumName;
    }

    public function setPersonName(string $personName) {
        Security::validatePersonName($personName);

        $this->personName = $personName;
    }

    public function setPersonEmail(string $personEmail) {
        Security::validateEmail($personEmail);

        $this->personEmail = $personEmail;
    }

    public function setPersonCity(string $personCity) {
        Security::validateCity($personCity);

        $this->personCity = $personCity;
    }

    public function setPersonUF(string $personUF) {
        Security::validateUF($personUF);

        $this->personUF = $personUF;
    }

    public function setPersonPhones (array $personPhones) {
        Security::validatePhone($personPhones['principal']);
        if (!empty($personPhones['secondary'])) {
            Security::validatePhone($personPhones['secondary']);
        }

        $this->personPhones = $personPhones;
    }

    public function setPersonSocialNetworks (array $personSocialNetworks) {
        foreach ($personSocialNetworks as $social => $link) {
            if ($link) {
                Security::validateLink($link, $social);
            }
        }

        if (!empty($personSocialNetworks)) {
            $this->personSocialNetworks = $personSocialNetworks;
        } else {
            $this->personSocialNetworks = [];
        }
    }

    public function setPersonEducation (array $personEducation) {
        foreach ($personEducation as $course => $courseInfo) {
            if ($course) {
                Security::validateEmpty($courseInfo['course_name'], 'nome do curso');
                Security::validateEmpty($courseInfo['course_modality'], 'modalidade');
                Security::validateEmpty($courseInfo['institution'], 'instituição');
                Security::validateEmpty($courseInfo['status'], 'status');
                Security::validateDateEmpty(Security::formatDate($courseInfo['init']), 'curso - início');
                Security::validateDateEmpty(Security::formatDate($courseInfo['finish']), 'curso - fim');
            }
        }

        if (!empty($personEducation)) {
            $this-> personEducation = $personEducation;
        } else {
            $this-> personEducation = [];
        }
    }

    public function setPersonSkills (array $personSkills) {
        foreach ($personSkills as $key => $skill) {
            $personSkills[$key] = Security::sanitizeString($skill);
        }

        if (!empty($personSkills)) {
            $this->personSkills = $personSkills;
        } else {
            $this->personSkills = [];
        }
    }

    public function setPersonLanguages (array $personLanguages) {
        foreach ($personLanguages as $key => $lang) {
            $personLanguages[$key] = Security::sanitizeString($lang);
        }

        if (!empty($personLanguages)) {
            $this->personLanguages = $personLanguages;
        } else {
            $this->personLanguages = [];
        }
    }

    public function setPersonExperiences (array $personExperiences) {
        foreach ($personExperiences as $enterprise => $info) {
            if ($enterprise) {
                Security::validateEmpty($info['office'], 'cargo');
                Security::validateDateEmpty(Security::formatDate($info['init']), 'emprego - inicio');
                Security::validateDateEmpty(Security::formatDate($info['finish']), 'emprego - término');
                Security::validateEmpty($info['activities'], 'atividades');
            }
        }
        if (!empty($personExperiences)) {
            $this->personExperiences = $personExperiences;
        } else {
            $this->personExperiences = [];
        }
    }

    public function setPersonBirthDate(DateTime $personBirthDate) {
        Security::validateDate($personBirthDate);

        $this->personBirthDate = $personBirthDate;
    }

    public function setPersonDescription(string $personDescription) {
        Security::validatePersonDescription($personDescription);

        $this->$personDescription = $personDescription;
    }

    public function getPersonID() {
        return $this->personID;
    }

    public function getCurriculumName() {
        return $this->curriculumName;
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

    public function getPersonDescription() {
        return $this->personDescription;
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

    public function insert(CurriculumModel $curriculum) {
        var_dump($curriculum);
    }
}