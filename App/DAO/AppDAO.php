<?php

namespace App\DAO;

use App\Exceptions\SqlQueryException;

class AppDAO {
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

    public function searchByParams(array $params) {
        $dbase = $this->database;
        $query = $this->buildQuery($params);

        $curriculums = $dbase->fetchAll($query);
        if (!$curriculums) {
            throw new SqlQueryException('Não foram encontrados currículos com estes parâmetros');
        }

        return $curriculums;
    }

    public function getCurriculumName(int $curriculumID) {
        $dbase = $this->database;

        $query = "
            SELECT
                CV.cv_name AS name
            FROM
                " . self::TABLE_CV . " CV
            WHERE
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
                AND CV.status = '" . self::STATUS_ACTIVE . "'
            LIMIT 1
        ";

        $name = $dbase->fetchAssoc($query);
        if (!$name) {
            throw new SqlQueryException('Currículo não encontrado');  
        }

        return $name['name'];
    }

    public function getPersonalInfo(int $curriculumID) {
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
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
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

    public function getPersonalContact(int $curriculumID) {
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
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
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

    public function getPersonalEducation(int $curriculumID) {
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
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
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

    public function getPersonalExperience(int $curriculumID) {
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
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
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

    public function getPersonalSkills(int $curriculumID) {
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
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
                AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $skills = $dbase->fetchAll($query);

        $skillsArray = [];
        foreach ($skills as $skill) {
            array_push($skillsArray, $skill['person_skill_name']);
        }

        return $skillsArray;
    }

    public function getPersonalLangs(int $curriculumID) {
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
                CV.id = '" . $dbase->scapeString($curriculumID) . "'
                AND CV.status = '" . self::STATUS_ACTIVE . "'
        ";

        $langs = $dbase->fetchAll($query);

        $langsArray = [];
        foreach ($langs as $lang) {
            array_push($langsArray, $lang['person_lang_name']);
        }

        return $langsArray;
    }

    /**
     * Build a sql query by params defined
     * 
     * @param array $params Params to search
     * @return string Database query
     */
    private function buildQuery(array $params) : string {
        $query = "
            SELECT
                UC.id AS curriculum_id,
                UC.cv_name AS curriculum_name,
                PI.person_name,
                PC.person_email,
                PC.person_phone_principal
            FROM
                users_curriculum UC
            INNER JOIN
                users_personal_infos PI
                    ON PI.curriculum_id = UC.id
                    " . $this->buildInnerJoinPersonalInfo($params) . "
            INNER JOIN
                users_personal_contacts PC
                    ON PC.curriculum_id = UC.id
            INNER JOIN
                users_personal_educations PE
                    ON PE.curriculum_id = UC.id
                    " . $this->buildInnerJoinPersonalEducation($params) . "
            INNER JOIN
                users_personal_experiences PX
                    ON PX.curriculum_id = UC.id
                    " . $this->buildInnerJoinPersonalExperience($params) . "
            INNER JOIN
                users_personal_langs PL
                    ON PL.curriculum_id = UC.id
                    " . $this->buildInnerJoinPersonalLangs($params) . "
            INNER JOIN
                users_personal_skills PS
                    ON PS.curriculum_id = UC.id
                        " . $this->buildInnerJoinPersonalSkills($params) . "
            WHERE
                UC.status = '" . self::STATUS_ACTIVE . "'
            GROUP BY
                UC.id
        ";

        return $query;
    }

    private function buildInnerJoinPersonalInfo(array $params) {
        $inner = '';

        if (isset($params['person_cities'])) {
            $inner .= ' AND PI.person_city IN (' . $this->prepareFields($params['person_cities']) . ')';
        }
        if (isset($params['person_uf'])) {
            $inner .= ' AND PI.person_uf IN (' . $this->prepareFields($params['person_uf']) . ')';
        }

        return $inner;
    }

    private function buildInnerJoinPersonalEducation(array $params) {
        $inner = '';

        if (isset($params['education_acronym'])) {
            $inner .= ' AND PE.person_course_acronym IN (' . $this->prepareFields($params['education_acronym']) . ')';
        }
        if (isset($params['education_modality'])) {
            $inner .= ' AND PE.person_course_modality IN (' . $this->prepareFields($params['education_modality']) . ')';
        }
        if (isset($params['education_institution'])) {
            $inner .= ' AND PE.person_course_institution IN (' . $this->prepareFields($params['education_institution']) . ')';
        }
        if (isset($params['education_status'])) {
            $inner .= ' AND PE.person_course_status IN (' . $this->prepareFields($params['education_status']) . ')';
        }

        return $inner;
    }

    private function buildInnerJoinPersonalExperience(array $params) {
        $inner = '';

        if (isset($params['experience_enterprise'])) {
            $inner .= ' AND PX.person_experience_enterprise IN (' . $this->prepareFields($params['experience_enterprise']) . ')';
        }
        if (isset($params['experience_office'])) {
            $inner .= ' AND PX.person_experience_office IN (' . $this->prepareFields($params['experience_office']) . ')';
        }

        return $inner;
    }

    private function buildInnerJoinPersonalLangs(array $params) {
        $inner = '';

        if (isset($params['person_langs'])) {
            $inner .= ' AND PL.person_lang_name IN (' . $this->prepareFields($params['person_langs']) . ')';
        }

        return $inner;
    }

    private function buildInnerJoinPersonalSkills(array $params) {
        $inner = '';

        if (isset($params['person_skills'])) {
            $inner .= ' AND PS.person_skill_name IN (' . $this->prepareFields($params['person_skills']) . ')';
        }

        return $inner;
    }


    /**
     * Convert array in fields sql to search
     * 
     * @param array $fields - Fields to convert
     * @return string String with fields sql
     */
    private function prepareFields(array $fields) : string {
        $fieldsSanitized = array_map(
                function($value) {
                    return "'" . $this->database->scapeString($value) . "'";
                }, $fields
        );

        return implode(',', $fieldsSanitized);
    }
}