<?php

namespace App\DAO;

use App\Exceptions\SqlQueryException;
use App\Models\CurriculumModel;

class CurriculumDAO {
    private $database;

    const TABLE_CV = 'users_curriculum';
    const TABLE_PI = 'users_personal_infos';
    const TABLE_PC = 'users_personal_contacts';
    const TABLE_PE = 'users_personal_educations';
    const TABLE_PEX = 'users_personal_experiences';
    const TABLE_PL = 'users_personal_langs';
    const TABLE_PS = 'users_personal_skills';

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public function __construct() {
        $this->database = new Database();
    }

    public function createCurriculum(array $data) {
        $dbase = $this->database;

        $idCurriculum = $dbase->insertWithArray(self::TABLE_CV, $data);

        return $idCurriculum;
    }

    public function countPersonCurriculum($userID) {
        $dbase = $this->database;

        $query = "
            SELECT
                COUNT(CV.id) AS qnt
            FROM
                " . self::TABLE_CV . " CV
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $quantity = $dbase->fetchAssoc($query);

        return (int) $quantity['qnt'];
    }

    public function insertPersonalInfo(array $personalInfo) {
        $dbase = $this->database;

        $idPersonalInfo = $dbase->insertWithArray(self::TABLE_PI, $personalInfo);

        return $idPersonalInfo;
    }

    public function insertPersonalContact(array $personalContact) {
        $dbase = $this->database;

        $idPersonalContact = $dbase->insertWithArray(self::TABLE_PC, $personalContact);

        return $idPersonalContact;
    }

    public function insertPersonalEducation(array $personalEducation) {
        $dbase = $this->database;

        $idPersonalEducation = $dbase->insertWithArray(self::TABLE_PE, $personalEducation);

        return $idPersonalEducation;
    }

    public function insertPersonalExperience(array $personalExperience) {
        $dbase = $this->database;

        $idPersonalExperience = $dbase->insertWithArray(self::TABLE_PEX, $personalExperience);

        return $idPersonalExperience;
    }

    public function insertPersonalSkills(array $personalSkills) {
        $dbase = $this->database;

        $idPersonalSkills = $dbase->insertWithArray(self::TABLE_PS, $personalSkills);

        return $idPersonalSkills;
    }

    public function insertPersonalLangs(array $personalLangs) {
        $dbase = $this->database;

        $idPersonalLangs = $dbase->insertWithArray(self::TABLE_PL, $personalLangs);

        return $idPersonalLangs;
    }

    public function getCurriculumName(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                CV.cv_name AS name
            FROM
                " . self::TABLE_CV . " CV
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
            LIMIT 1
        ";

        $name = $dbase->fetchAssoc($query);
        if (!$name) {
            throw new SqlQueryException('Currículo não encontrado');  
        }

        return $name['name'];
    }

    public function getPersonalInfo(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                PI.person_name,
                PI.person_city,
                PI.person_uf,
                PI.person_birth,
                PI.person_desc
            FROM
                " . self::TABLE_CV . " CV
            INNER JOIN
                " . self::TABLE_PI . " PI
                    ON PI.curriculum_id = CV.id
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
            LIMIT 1


        ";

        $infos = $dbase->fetchAssoc($query);
        if (!$infos) {
            throw new SqlQueryException('Informações Pessoais não encontradas');  
        }

        $infoArray = [
            'name' => $infos['person_name'],
            'city' => $infos['person_city'],
            'uf' => $infos['person_uf'],
            'birthdate' => $infos['person_birth'],
            'description' => $infos['person_desc'],
        ];

        return $infoArray;
    }

    public function getPersonalContact(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                PC.person_email,
                PC.person_phone_principal,
                PC.person_phone_secondary,
                PC.person_linkedin,
                PC.person_facebook,
                PC.person_instagram,
                PC.person_site
            FROM
                " . self::TABLE_CV . " CV
            INNER JOIN
                " . self::TABLE_PC . " PC
                    ON PC.curriculum_id = CV.id
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
            LIMIT 1
        ";

        $contact = $dbase->fetchAssoc($query);
        if (!$contact) {
            throw new SqlQueryException('Contatos não encontrados');  
        }

        $contactArray['email'] = $contact['person_email'];
        $contactArray['phones'] = [
            'principal' => $contact['person_phone_principal'],
            'secondary' => $contact['person_phone_secondary'],
        ];
        $contactArray['social_networks'] = [
            'linkedIn' => $contact['person_linkedin'],
            'facebook' => $contact['person_facebook'],
            'instagram' => $contact['person_instagram'],
            'site' => $contact['person_site'],
        ];

        return $contactArray;
    }

    public function getPersonalEducation(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                PE.id,
                PE.person_course_acronym,
                PE.person_course_name,
                PE.person_course_modality,
                PE.person_course_institution,
                PE.person_course_status,
                PE.person_course_init,
                PE.person_course_finish
            FROM
                " . self::TABLE_CV . " CV
            INNER JOIN
                " . self::TABLE_PE . " PE
                    ON PE.curriculum_id = CV.id
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $educations = $dbase->fetchAll($query);

        $arrayEducations = []; 
        foreach ($educations as $education) {
            $arrayEducations[$education['id']] = [
                'course_acronym' => $education['person_course_acronym'],
                'course_name' => $education['person_course_name'],
                'course_modality' => $education['person_course_modality'],
                'institution' => $education['person_course_institution'],
                'status' => $education['person_course_status'],
                'init' => $education['person_course_init'],
                'finish' => $education['person_course_finish'],
            ];
        }

        return $arrayEducations;
    }

    public function getPersonalExperience(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $dbase = $this->database;

        $query = "
            SELECT
                PEX.id,
                PEX.person_experience_enterprise,
                PEX.person_experience_office,
                PEX.person_experience_activities,
                PEX.person_experience_init,
                PEX.person_experience_finish
            FROM
                " . self::TABLE_CV . " CV
            INNER JOIN
                " . self::TABLE_PEX . " PEX
                    ON PEX.curriculum_id = CV.id
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $experiences = $dbase->fetchAll($query);
        
        $arrayExperiences = []; 
        foreach ($experiences as $experience) {
            $arrayExperiences[$experience['id']] = [
                'enterprise' => $experience['person_experience_enterprise'],
                'office' => $experience['person_experience_office'],
                'init' => $experience['person_experience_init'],
                'finish' => $experience['person_experience_finish'],
                'activities' => $experience['person_experience_activities'],
            ];
        }

        return $arrayExperiences;
    }

    public function getPersonalSkills(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                PS.person_skill_name
            FROM
                " . self::TABLE_CV . " CV
            INNER JOIN
                " . self::TABLE_PS . " PS
                    ON PS.curriculum_id = CV.id
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $skills = $dbase->fetchAll($query);

        $skillsArray = [];
        foreach ($skills as $skill) {
            array_push($skillsArray, $skill['person_skill_name']);
        }

        return $skillsArray;
    }

    public function getPersonalLangs(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                PL.person_lang_name
            FROM
                " . self::TABLE_CV . " CV
            INNER JOIN
                " . self::TABLE_PL . " PL
                    ON PL.curriculum_id = CV.id
            WHERE
                CV.user_id = '" . $dbase->scapeString($userID) . "'
                    AND CV.id = '" . $dbase->scapeString($curriculumID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $langs = $dbase->fetchAll($query);

        $langsArray = [];
        foreach ($langs as $lang) {
            array_push($langsArray, $lang['person_lang_name']);
        }

        return $langsArray;
    }

    public function listUserCurriculum(int $userID) {
        $dbase = $this->database;

        $query = "
            SELECT
                CV.id,
                CV.cv_name,
                CV.oficial,
                CV.updated
            FROM
                " . self::TABLE_CV . " CV
            WHERE
                CV.user_id = '" .  $dbase->scapeString($userID) . "'
                    AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $list = $dbase->fetchAll($query);

        if (!$list) {
            throw new SqlQueryException('Não foram encontrados currículos cadastrados');  
        }

        return $list;
    }

    public function disableCurriculum(int $userID, int $curriculumID) {
        $dbase = $this->database;

        $fields['status'] = self::STATUS_DELETED;
        $fields['updated'] = 'NOW()';

        $where['id'] = $curriculumID;
        $where['user_id'] = $userID;

        $dbase->update(self::TABLE_CV, $fields, $where);
    }

    public function updateName(int $userID, int $curriculumID, string $name) {
        $dbase = $this->database;

        $field['cv_name'] = $name;
        $field['updated'] = 'NOW()';

        $where['id'] = $curriculumID;
        $where['user_id'] = $userID;

        if (!$dbase->update(self::TABLE_CV, $field, $where)) {
            throw new SqlQueryException('Não foi possível atualizar o nome do currículo'); 
        }
    }

    public function setOfficial(int $userID, int $curriculumID, int $oficial) {
        self::unofficialCvs($userID);

        $dbase = $this->database;
        
        $field['oficial'] = $oficial;
        $field['updated'] = 'NOW()';

        $where['id'] = $curriculumID;
        $where['user_id'] = $userID;

        $dbase->update(self::TABLE_CV, $field, $where);
    }

    public function updatePersonalInfo(int $curriculumID, array $fields) {
        $dbase = $this->database;

        $where['curriculum_id'] = $curriculumID;

        $dbase->update(self::TABLE_PI, $fields, $where);
    }

    public function updatePersonalContact(int $curriculumID, array $fields) {
        $dbase = $this->database;

        $where['curriculum_id'] = $curriculumID;

        $dbase->update(self::TABLE_PC, $fields, $where);
    }

    public function updatePersonalLangs(int $curriculumID, array $langs) {
        self::deletePersonLangs($curriculumID);

        $dbase = $this->database;

        foreach ($langs as $lang) {
            $arrayInsert = [];
            $arrayInsert['curriculum_id'] = $curriculumID;
            $arrayInsert['person_lang_name'] = $lang;

            $dbase->insertWithArray(self::TABLE_PL, $arrayInsert);
        }
    }

    public function updatePersonalSkills(int $curriculumID, array $skills) {
        self::deletePersonSkills($curriculumID);

        $dbase = $this->database;

        foreach ($skills as $skill) {
            $arrayInsert = [];
            $arrayInsert['curriculum_id'] = $curriculumID;
            $arrayInsert['person_skill_name'] = $skill;

            $dbase->insertWithArray(self::TABLE_PS, $arrayInsert);
        }
    }

    public function updatePersonEducation(int $curriculumID, array $fields) {
        $dbase = $this->database;

        foreach ($fields as $id => $info) {
            $where = [];
            $where['id'] = $id;
            $where['curriculum_id'] = $curriculumID;

            $dbase->update(self::TABLE_PE, $info, $where);
        }
    }

    public function updatePersonExperiences(int $curriculumID, array $fields) {
        $dbase = $this->database;

        foreach ($fields as $id => $info) {
            $where = [];
            $where['id'] = $id;
            $where['curriculum_id'] = $curriculumID;

            $dbase->update(self::TABLE_PEX, $info, $where);
        }
    }

    private function unofficialCvs(int $userID) {
        $field['oficial'] = 0;

        $where['oficial'] = 1;
        $where['user_id'] = $userID;

        $this->database->update(self::TABLE_CV, $field, $where);
    }

    private function deletePersonLangs(int $curriculumID) {
        $where['curriculum_id'] = $curriculumID;
        $limit = CurriculumModel::MAX_LANGS;
        
        $this->database->delete(self::TABLE_PL, $where, $limit);
    }

    private function deletePersonSkills(int $curriculumID) {
        $where['curriculum_id'] = $curriculumID;
        $limit = CurriculumModel::MAX_SKILLS;
        
        $this->database->delete(self::TABLE_PS, $where, $limit);
    }

}