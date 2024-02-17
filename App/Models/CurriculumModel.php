<?php

namespace App\Models;

use App\DAO\CurriculumDAO;
use DateTime;
use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

class CurriculumModel {
    private string $personID;
    private int $curriculumID = 0;
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

    public function setCurriculumID(string $curriculumID) {
        if ($curriculumID < 0) {
            throw new InvalidParamException('Parãmetro ID do currículo obrigatório');
        }

        $this->curriculumID = $curriculumID;
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
        $personalInfo['person_birth'] = Security::convertDateToString($curriculum->getPersonBirthDate());
        $personalInfo['person_desc'] = $curriculum->getPersonDescription();

        $idPersonalInfo = $dao->insertPersonalInfo($personalInfo);
        if (!$idPersonalInfo) {
            throw new SqlQueryException('Não foi possível inserir as informações pessoais');
        }

        $phones = $curriculum->getPersonPhones();
        $socialNetworks = $curriculum->getPersonSocialNetworks();
        $personalContact['curriculum_id'] = $idCurriculum;
        $personalContact['person_email'] = $curriculum->getPersonEmail();
        $personalContact['person_phone_principal'] = $phones['principal'];
        $personalContact['person_phone_secondary'] = $phones['secondary'];
        $personalContact['person_linkedin'] = $socialNetworks['linkedIn'];
        $personalContact['person_facebook'] = $socialNetworks['facebook'];
        $personalContact['person_instagram'] = $socialNetworks['instagram'];
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

    public function get(int $userID, int $curriculumID) {
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

    public function list(int $userID) {
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

    public function update(int $userID, int $curriculumID, array $fieldsToUpdate) {
        $dao = $this->DAO;

        if (isset($fieldsToUpdate['curriculum_name'])) {
            $dao->updateName($userID, $curriculumID, $fieldsToUpdate['curriculum_name']);
        }

        if (isset($fieldsToUpdate['oficial'])) {
            $dao->setOfficial($userID, $curriculumID, $fieldsToUpdate['oficial']);
        }

        if (isset($fieldsToUpdate['personal_info'])) {
            $dao->updatePersonalInfo($curriculumID, $fieldsToUpdate['personal_info']);
        }

        if (isset($fieldsToUpdate['personal_contact'])) {
            $dao->updatePersonalContact($curriculumID, $fieldsToUpdate['personal_contact']);
        }
    
        if (isset($fieldsToUpdate['personal_education'])) {
            $dao->updatePersonEducation($curriculumID, $fieldsToUpdate['personal_education']);
        }
    
        if (isset($fieldsToUpdate['personal_experience'])) {
            $dao->updatePersonExperiences($curriculumID, $fieldsToUpdate['personal_experience']);
        }

        if (isset($fieldsToUpdate['personal_skills'])) {
            $dao->updatePersonalSkills($curriculumID, $fieldsToUpdate['personal_skills']);
        }

        if (isset($fieldsToUpdate['personal_languages'])) {
            $dao->updatePersonalLangs($curriculumID, $fieldsToUpdate['personal_languages']);
        }
    }

    public static function makeArrayUpdateCvName(string $curriculumName) {
        $fieldsToUpdate = [];
    
        $fieldsToUpdate['curriculum_name'] = Security::removeDoubleSpace($curriculumName);
        
        return $fieldsToUpdate;
    }

    public static function makeArrayCvOfficial(int $oficial) {
        $fieldsToUpdate = [];

        if (!in_array($oficial, [0, 1])) {
            throw new InvalidParamException('Status Inválido');
        }

        $fieldsToUpdate['oficial'] = $oficial;

        return $fieldsToUpdate;
    }

    public static function makeArrayUpdatePersonalInfo(array $personalInfo) {
        $fieldsToUpdate = [];

        if (!empty($personalInfo['name'])) {
            $fieldsToUpdate['personal_info']['person_name'] = Security::removeDoubleSpace(Security::fixName($personalInfo['name']));
        }
        if(!empty($personalContact['email'])) {
            $fieldsToUpdate['personal_contact']['person_email'] = Security::validateEmail($personalContact['email']);
        }
        if (!empty($personalInfo['city'])) {
            $fieldsToUpdate['personal_info']['person_city'] = (Security::removeDoubleSpace(Security::fixName($personalInfo['city'])));
        }
        if (!empty($personalInfo['uf'])) {
            $fieldsToUpdate['personal_info']['person_uf'] = strtoupper($personalInfo['uf']);
        }
        if (!empty($personalInfo['birthdate'])) {
            $fieldsToUpdate['personal_info']['person_birth'] = $personalInfo['birthdate'];
        }
        if (!empty($personalInfo['description'])) {
            $fieldsToUpdate['personal_info']['person_desc'] = Security::sanitizeString($personalInfo['description']);
        }

        return $fieldsToUpdate;
    }

    public static function makeArrayUpdatePersonalContact(array $personalContact) {
        $fieldsToUpdate = [];

        $phones = $personalContact['phones'];
        if(!empty($phones['principal'])) {
            $fieldsToUpdate['personal_contact']['person_phone_principal'] = Security::validatePhone($phones['principal']);
        }
        if(!empty($phones['secondary'])) {
            $fieldsToUpdate['personal_contact']['person_phone_secondary'] = Security::validatePhone($phones['secondary']);
        }

        $networks = $personalContact['social_networks'];
        if (!empty($networks)) {
            if (!empty($networks['linkedIn'])) { 
                $fieldsToUpdate['personal_contact']['person_linkedin'] = Security::validateLink($networks['linkedIn']);
            }
            if (!empty($networks['facebook'])) { 
                $fieldsToUpdate['personal_contact']['person_facebook'] = Security::validateLink($networks['facebook']);
            }
            if (!empty($networks['instagram'])) { 
                $fieldsToUpdate['personal_contact']['person_instagram'] = Security::validateLink($networks['instagram']);
            }
            if (!empty($networks['site'])) { 
                $fieldsToUpdate['personal_contact']['person_site'] = Security::validateLink($networks['site']);
            }
        }

        return $fieldsToUpdate;
    }

    public static function makeArrayUpdatePersonalEducation(array $personalEducation) {
        $fieldsToUpdate = [];
    
        foreach ($personalEducation as $courseInfo) {
            $key = Security::filterInt($courseInfo['id']);

            if (!empty($key)) {
                if (!empty($courseInfo['course_acronym'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_acronym'] = Security::validateEmpty($courseInfo['course_acronym'], 'sigla do curso');
                }
                if (!empty($courseInfo['course_name'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_name'] = Security::validateEmpty($courseInfo['course_name'], 'nome do curso');
                }
                if (!empty($courseInfo['course_modality'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_modality'] = Security::validateEmpty($courseInfo['course_modality'], 'modalidade');
                }
                if (!empty($courseInfo['institution'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_institution'] = Security::validateEmpty($courseInfo['institution'], 'instituição');
                }
                if (!empty($courseInfo['status'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_status'] = Security::validateNumber($courseInfo['status'], 'status');
                }
                if (!empty($courseInfo['init'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_init'] =  Security::validateDateEmpty(Security::formatDate($courseInfo['init']), 'curso - início');
                }
                if (!empty($courseInfo['finish'])) {
                    $fieldsToUpdate['personal_education'][$key]['person_course_finish'] = Security::validateDateEmpty(Security::formatDate($courseInfo['finish']), 'curso - fim');
                }
            }
        }

        return $fieldsToUpdate;
    }

    public static function makeArrayUpdatePersonalExperience(array $personalExperience) {
        $fieldsToUpdate = [];

        foreach ($personalExperience as $jobInfo) {
            $key = Security::filterInt($jobInfo['id']);

            if (!empty($jobInfo['id'])) {
                if (!empty($jobInfo['enterprise'])) {
                    $fieldsToUpdate['personal_experience'][$key]['person_experience_enterprise'] = Security::validateEmpty($jobInfo['enterprise'], 'empresa');
                }
                if (!empty($jobInfo['office'])) {
                    $fieldsToUpdate['personal_experience'][$key]['person_experience_office'] = Security::validateEmpty($jobInfo['office'], 'cargo');
                }
                if (!empty($jobInfo['init'])) {
                    $fieldsToUpdate['personal_experience'][$key]['person_experience_init'] = Security::validateDateEmpty(Security::formatDate($jobInfo['init']), 'emprego - inicio');
                }
                if (!empty($jobInfo['finish'])) {
                    $fieldsToUpdate['personal_experience'][$key]['person_experience_finish'] = Security::validateDateEmpty(Security::formatDate($jobInfo['finish']), 'emprego - término');;
                }
                if (!empty($jobInfo['activities'])) {
                    $fieldsToUpdate['personal_experience'][$key]['person_experience_activities'] = Security::validateEmpty($jobInfo['activities'], 'atividades');;
                }
            }
        }

        return $fieldsToUpdate;
    }

    public static function makeArrayUpdatePersonalSkills(array $personalSkills) {
        $fieldsToUpdate = [];
        $arraySkills = [];

        foreach ($personalSkills as $skill) {
            array_push($arraySkills, Security::sanitizeString($skill));
        }
        $fieldsToUpdate['personal_skills'] = $arraySkills;

        return $fieldsToUpdate;
    }

    public static function makeArrayUpdatePersonalLangs(array $personalLangs) {
        $fieldsToUpdate = [];
        $arrayLangs = [];

        foreach ($personalLangs as $lang) {
            array_push($arrayLangs, Security::sanitizeString($lang));
        }
        $fieldsToUpdate['personal_languages'] = $arrayLangs;
    
        return $fieldsToUpdate;
    }
}