<?php

namespace App\Models;

use App\DAO\AppDAO;
use App\Exceptions\InvalidParamException;

class AppModel {
    private $DAO;

    public function __construct() {
        $this->DAO = new AppDAO();
    }

    const MAX_REQUEST_PARAMS = 3;

    public function search(array $params) {
        $dao = $this->DAO;

        self::validateParams($params);
        self::checkSizeRequest($params);

        return $dao->searchByParams($params);
    }

    public function view($id) {
        $dao = $this->DAO;

        $curriculum = [];

        $curriculum['curriculum_name'] = $dao->getCurriculumName($id);
        $curriculum['personal_info'] = $dao->getPersonalInfo($id);
        $curriculum['personal_contact'] = $dao->getPersonalContact($id);
        $curriculum['personal_education'] = $dao->getPersonalEducation($id);
        $curriculum['personal_skills'] = $dao->getPersonalSkills($id);
        $curriculum['personal_languages'] = $dao->getPersonalLangs($id);
        $curriculum['personal_experience'] = $dao->getPersonalExperience($id);

        return $curriculum;
    }

    public function validateParams(array $params) {
        $validParams = [
            'person_cities', 'person_uf', 'person_skills','person_langs',
            'education_acronym', 'education_modality', 'education_institution',
            'education_status', 'experience_enterprise', 'experience_office'
        ];

        foreach ($params as $param => $value) {
            if (!in_array($param, $validParams)) {
                throw new InvalidParamException('Parâmetro "' . $param . '" inválido. Verifique-o e tente novamente');
            }
        }
    }

    /**
     * Checks the size of search fields, raises an exception if it exceeds the allowed limit
     * 
     * @param array $params Fields to search
     * @return void
     */
    public static function checkSizeRequest(array $params) {
        foreach ($params as $param => $values) {
            if (count($values) > self::MAX_REQUEST_PARAMS) {
                throw new InvalidParamException('Campo "' . $param . '" excede o limite de ' . self::MAX_REQUEST_PARAMS . ' parâmetros. Verifique-os e tente novamente');
            }
        }
    }
}