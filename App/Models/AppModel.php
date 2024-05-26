<?php

namespace App\Models;

use App\DAO\AppDAO;
use App\DAO\UserDAO;
use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;
use Firebase\JWT\JWT;

class AppModel {
    private $DAO;

    public function __construct() {
        $this->DAO = new AppDAO();
    }

    const MAX_REQUEST_PARAMS = 3;

    public function getJwtToken(string $user, string $password) {
        $daoUser = new UserDAO();

        $userData = $daoUser->getAuthUser($user, $password);
        if (!$userData) {
            throw new SqlQueryException('Usuário ou senha incorretos', Http::UNAUTHORIZED);
        }
        if ($userData['status'] == 0) {
            throw new SqlQueryException('Conta do usuário inativa, entre em contato com o suporte para mais informações.', Http::UNAUTHORIZED);
        }

        return $this->generateJwtToken($userData);
    }

    private function generateJwtToken(array $userData) {
        $payload = [
            'exp' => time() + 86400000,
            'iat' => time(),
            'email' => $userData['email'],
            'id' => $userData['id'],
            'name' => $userData['name'],
        ];

        return JWT::encode($payload, Security::getJwtkey(), 'HS256');
    }

    public function search(array $params) {
        $dao = $this->DAO;

        $filteredParams = self::validateParams($params);
        return $dao->searchByParams($filteredParams);
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

        foreach ($params as $param => $values) {
            if (!in_array($param, $validParams)) {
                throw new InvalidParamException('Parâmetro "' . $param . '" inválido. Verifique-o e tente novamente');
            }

            if (empty($values)) {
                unset($params[$param]);
            } else {
                if (count($values) > self::MAX_REQUEST_PARAMS) {
                    throw new InvalidParamException('Campo "' . $param . '" excede o limite de ' . self::MAX_REQUEST_PARAMS . ' parâmetros. Verifique-os e tente novamente');
                }
            }
        }

        return $params;
    }
}