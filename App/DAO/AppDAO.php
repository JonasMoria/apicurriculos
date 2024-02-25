<?php

namespace App\DAO;

use App\Exceptions\SqlQueryException;
use App\Models\Security;

class AppDAO {
    private $database;

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