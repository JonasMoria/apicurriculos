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
                Security::validateNumber($courseInfo['status'], 'status');
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

        $this->personDescription = $personDescription;
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
        $dao = $this->DAO;

        $curriculumData['user_id'] = $curriculum->getPersonID();
        $curriculumData['cv_name'] = $curriculum->getCurriculumName();

        $idCurriculum = $dao->createCurriculum($curriculumData);
        if (!$idCurriculum) {
            throw new InvalidArgumentException('Não foi possível criar o currículo');
        }

        $personalInfo['curriculum_id'] = $idCurriculum;
        $personalInfo['person_name'] = $curriculum->getPersonName();
        $personalInfo['person_city'] = $curriculum->getPersonCity();
        $personalInfo['person_uf'] =  $curriculum->getPersonUF();
        $personalInfo['person_email'] = $curriculum->getPersonEmail();
        $personalInfo['person_birth'] = Security::convertDateToString($curriculum->getPersonBirthDate());
        $personalInfo['person_desc'] = $curriculum->getPersonDescription();

        $idPersonalInfo = $dao->insertPersonalInfo($personalInfo);
        if (!$idPersonalInfo) {
            throw new InvalidArgumentException('Não foi possível inserir as informações pessoais');
        }

        $phones = $curriculum->getPersonPhones();
        $socialNetworks = $curriculum->getPersonSocialNetworks();
        $personalContact['curriculum_id'] = $idCurriculum;
        $personalContact['person_phone_principal'] = $phones['principal'];
        $personalContact['person_phone_secondary'] = $phones['secondary'];
        $personalContact['person_linkedin'] = $socialNetworks['linkedIn'];
        $personalContact['person_facebook'] = $socialNetworks['facebook'];
        $personalContact['person_instragram'] = $socialNetworks['instagram'];
        $personalContact['person_site'] = $socialNetworks['site'];

        $idPersonalContact = $dao->insertPersonalContact($personalContact);
        if (!$idPersonalContact) {
            throw new InvalidArgumentException('Não foi possível inserir as informações de contato');
        }

        $personalEducations = $curriculum->getPersonEducation();
        foreach ($personalEducations as $acronym => $infos) {
            $education = [];
            $education['curriculum_id'] = $idCurriculum;
            $education['person_course_acronym'] =  $acronym;
            $education['person_course_name'] = $infos['course_name'];
            $education['person_course_modality'] = $infos['course_modality'];
            $education['person_course_institution'] = $infos['institution'];
            $education['person_course_status'] = $infos['status'];
            $education['person_course_init'] = $infos['init'];
            $education['person_course_finish'] = $infos['finish'];

            $idPersonalEducation = $dao->insertPersonalEducation($education);
            if (!$idPersonalContact) {
                throw new InvalidArgumentException('Não foi possível inserir as informações de educação');
            }
        }

        $personalExperiences = $curriculum->getPersonExperiences();
        foreach ($personalExperiences as $enterprise => $jobInfo) {
            $job = [];
            $job['curriculum_id'] = $idCurriculum;
            $job['person_experience_enterprise'] = $enterprise;
            $job['person_experience_office'] = $jobInfo['office'];
            $job['person_experience_activities'] = $jobInfo['activities'];
            $job['person_experience_init'] = $jobInfo['init'];
            $job['person_experience_finish'] = $jobInfo['finish'];

            $idPersonalExperience = $dao->insertPersonalExperience($job);
            if (!$idPersonalExperience) {
                throw new InvalidArgumentException('Não foi possível inserir as informações de experiência');
            }
        }

        $personalSkills = $curriculum->getPersonSkills();
        foreach ($personalSkills as $key => $skill) {
            $skillDB = [];
            $skillDB['curriculum_id'] = $idCurriculum;
            $skillDB['person_skill_name'] = $skill;

            $idPersonalSkills = $dao->insertPersonalSkills($skillDB);
            if (!$idPersonalSkills) {
                throw new InvalidArgumentException('Não foi possível inserir as informações de habilidades');
            }
        }

        $personalLangs = $curriculum->getPersonLanguages();
        foreach ($personalLangs as $key => $lang) {
            $langDB = [];
            $langDB['curriculum_id'] = $idCurriculum;
            $langDB['person_lang_name'] = $lang;

            $idPersonalLangs = $dao->insertPersonalLangs($langDB);
            if (!$idPersonalLangs) {
                throw new InvalidArgumentException('Não foi possível inserir as informações de linguagens');
            }
        }
    }
}