<?php

namespace App\Models;

use App\DAO\CurriculumDAO;
use DateTime;
use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

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

    const MAX_CURRICULUM = 3;

    const MAX_SOCIAL_NETWORKS = 3;
    const MAX_EDUCATION = 4;
    const MAX_EXPERIENCES = 4;
    const MAX_LANGS = 4;
    const MAX_SKILLS = 10;

    public function __construct() {
        $this->DAO = new CurriculumDAO();
    }

    public function setPersonID(string $personID) {
        if ($personID < 0) {
            throw new InvalidParamException('Campo ID do usuário obrigatório');
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
        $count = 0;
        foreach ($personSocialNetworks as $social => $link) {
            if ($count > self::MAX_SOCIAL_NETWORKS) {
                throw new InvalidParamException('Máximo de ' . self::MAX_SOCIAL_NETWORKS . ' Redes Sociais já atingido. Cadastre outro currículo para as demais redes sociais');
            }

            if ($link) {
                Security::validateLink($link, $social);
                $count++;
            }
        }

        if (!empty($personSocialNetworks)) {
            $this->personSocialNetworks = $personSocialNetworks;
        } else {
            $this->personSocialNetworks = [];
        }
    }

    public function setPersonEducation (array $personEducation) {
        $count = 0;
        foreach ($personEducation as $course => $courseInfo) {
            if ($count > self::MAX_EDUCATION) {
                throw new InvalidParamException('Máximo de ' . self::MAX_EDUCATION . ' Formações já atingido. Cadastre outro currículo para as demais formações');
            }

            if ($course) {
                Security::validateEmpty($courseInfo['course_name'], 'nome do curso');
                Security::validateEmpty($courseInfo['course_modality'], 'modalidade');
                Security::validateEmpty($courseInfo['institution'], 'instituição');
                Security::validateNumber($courseInfo['status'], 'status');
                Security::validateDateEmpty(Security::formatDate($courseInfo['init']), 'curso - início');
                Security::validateDateEmpty(Security::formatDate($courseInfo['finish']), 'curso - fim');
                $count++;
            }
        }

        if (!empty($personEducation)) {
            $this-> personEducation = $personEducation;
        } else {
            $this-> personEducation = [];
        }
    }

    public function setPersonSkills (array $personSkills) {
        $count = 0;
        foreach ($personSkills as $key => $skill) {
            if ($count > self::MAX_SKILLS) {
                throw new InvalidParamException('Máximo de ' . self::MAX_SKILLS . ' Habilidades já atingido. Cadastre outro currículo para as demais habilidades');
            }

            $personSkills[$key] = Security::sanitizeString($skill);
            $count++;
        }

        if (!empty($personSkills)) {
            $this->personSkills = $personSkills;
        } else {
            $this->personSkills = [];
        }
    }

    public function setPersonLanguages (array $personLanguages) {
        $count = 0;
        foreach ($personLanguages as $key => $lang) {
            if ($count >= self::MAX_LANGS) {
                throw new InvalidParamException('Máximo de ' . self::MAX_LANGS . ' Linguagens já atingido. Cadastre outro currículo para as demais linguagens');
            }

            $personLanguages[$key] = Security::sanitizeString($lang);
            $count++;
        }

        if (!empty($personLanguages)) {
            $this->personLanguages = $personLanguages;
        } else {
            $this->personLanguages = [];
        }
    }

    public function setPersonExperiences (array $personExperiences) {
        $count = 0;
        foreach ($personExperiences as $enterprise => $info) {
            if ($count > self::MAX_EXPERIENCES) {
                throw new InvalidParamException('Máximo de ' . self::MAX_EXPERIENCES . ' experiencias já atingido. Cadastre outro currículo para as demais experiências');
            }

            if ($enterprise) {
                Security::validateEmpty($info['office'], 'cargo');
                Security::validateDateEmpty(Security::formatDate($info['init']), 'emprego - inicio');
                Security::validateDateEmpty(Security::formatDate($info['finish']), 'emprego - término');
                Security::validateEmpty($info['activities'], 'atividades');
                $count++;
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

        if ($dao->countPersonCurriculum($curriculum->getPersonID()) >= self::MAX_CURRICULUM) {
            throw new InvalidParamException('Limite de ' . self::MAX_CURRICULUM . ' Currículos Cadastrados Atingido.');
        }

        $curriculumData['user_id'] = $curriculum->getPersonID();
        $curriculumData['cv_name'] = $curriculum->getCurriculumName();

        $idCurriculum = $dao->createCurriculum($curriculumData);
        if (!$idCurriculum) {
            throw new SqlQueryException('Não foi possível criar o currículo');
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
            throw new SqlQueryException('Não foi possível inserir as informações pessoais');
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
            throw new SqlQueryException('Não foi possível inserir as informações de contato');
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
                throw new SqlQueryException('Não foi possível inserir as informações de educação');
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
                throw new SqlQueryException('Não foi possível inserir as informações de experiência');
            }
        }

        $personalSkills = $curriculum->getPersonSkills();
        foreach ($personalSkills as $key => $skill) {
            $skillDB = [];
            $skillDB['curriculum_id'] = $idCurriculum;
            $skillDB['person_skill_name'] = $skill;

            $idPersonalSkills = $dao->insertPersonalSkills($skillDB);
            if (!$idPersonalSkills) {
                throw new SqlQueryException('Não foi possível inserir as informações de habilidades');
            }
        }

        $personalLangs = $curriculum->getPersonLanguages();
        foreach ($personalLangs as $key => $lang) {
            $langDB = [];
            $langDB['curriculum_id'] = $idCurriculum;
            $langDB['person_lang_name'] = $lang;

            $idPersonalLangs = $dao->insertPersonalLangs($langDB);
            if (!$idPersonalLangs) {
                throw new SqlQueryException('Não foi possível inserir as informações de linguagens');
            }
        }
    }

    public function get($userID, $curriculumID) {
        $dao = $this->DAO;

        $curriculum = [];

        $curriculum['curriculum_name'] = $dao->getCurriculumName($userID, $curriculumID);
        $curriculum['personal_info'] = $dao->getPersonalInfo($userID, $curriculumID);
        $curriculum['personal_contact'] = $dao->getPersonalContact($userID, $curriculumID);
        $curriculum['personal_education'] = $dao->getPersonalEducation($userID, $curriculumID);
        $curriculum['personal_skills'] = $dao->getPersonalSkills($userID, $curriculumID);
        $curriculum['personal_languages'] = $dao->getPersonalLangs($userID, $curriculumID);
        $curriculum['personal_experience'] = $dao->getPersonalExperience($userID, $curriculumID);

        return $curriculum;
    }

    public function list($userID) {
        $dao = $this->DAO;

        $getList = $dao->listUserCurriculum($userID);

        $listInfo = [];
        foreach ($getList as $key => $list) {
            $listInfo[$key]['curriculum_id'] = $list['id'];
            $listInfo[$key]['curriculum_name'] = $list['cv_name'];
            $listInfo[$key]['curriculum_oficial'] = $list['oficial'];
            $listInfo[$key]['curriculum_last_update'] = $list['updated'];
        }

        return $listInfo;
    }
}