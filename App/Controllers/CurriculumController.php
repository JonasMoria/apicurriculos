<?php

namespace App\Controllers;

use App\Models\CurriculumModel;
use App\Models\Http;
use App\Models\Security;
use Random\Engine\Secure;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

class CurriculumController {
    private $model;

    public function __construct() {
        $this->model = new CurriculumModel();
    }

    public function update(Request $request, Response $response, array $args) : Response {
        $curriculum = $this->model;
        $params = $request->getParsedBody();
        $userID = $_SESSION['user_id'];
        $curriculumID = Security::filterInt($args['id']);

        try {
            if (empty($params)) {
                throw new InvalidParamException('Não foram identificados dados a serem alterados');
            }

            $fieldsToUpdate = [];
            if (!empty($params['curriculum_name'])) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdateCvName($params['curriculum_name']));
            }

            $personalInfo = $params['personal_info'];
            if (!empty($personalInfo)) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdatePersonalInfo($personalInfo));
            }

            $personalContact = $params['personal_contact'];
            if (!empty($personalContact)) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdatePersonalContact($personalContact));
            }

            $personalEducation = $params['personal_education'];
            if (!empty($personalEducation)) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdatePersonalEducation($personalEducation));
            }

            $personalExperience = $params['personal_experience'];
            if (!empty($personalExperience)) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdatePersonalExperience($personalExperience));
            }

            $personalSkills = $params['personal_skills'];
            if (!empty($personalSkills)) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdatePersonalSkills($personalSkills));
            }

            $personalLangs = $params['personal_languages'];
            if (!empty($personalLangs)) {
                array_push($fieldsToUpdate, CurriculumModel::makeArrayUpdatePersonalLangs($personalLangs));
            }

            return Http::getJsonReponseSuccess($response, $fieldsToUpdate, 'Currículo Atualizado Com Sucesso', Http::CREATED);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function list(Request $request, Response $response, array $args) : Response {
        $curriculum = $this->model;
        $userID = $_SESSION['user_id'];

        try {
            $curriculumData = $curriculum->list($userID);
            return Http::getJsonReponseSuccess($response, $curriculumData, 'Sucesso', Http::OK);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function view(Request $request, Response $response, array $args) : Response {
        $curriculum = $this->model;
        $userID = $_SESSION['user_id'];
        $curriculumID = Security::filterInt($args['id']);
        
        try {
            $curriculumData = $curriculum->get($userID, $curriculumID);
            return Http::getJsonReponseSuccess($response, $curriculumData, 'Sucesso', Http::OK);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function new(Request $request, Response $response, array $args) : Response {
        $curriculum = $this->model;
        $params = $request->getParsedBody();

        try {

            $curriculum->setPersonId($_SESSION['user_id']);

            $curriculum->setCurriculumName(Security::removeDoubleSpace($params['curriculum_name']));

            $personalInfo = $params['personal_info'];
            $curriculum->setPersonName(Security::removeDoubleSpace(Security::fixName($personalInfo['name'])));
            $curriculum->setPersonCity(Security::removeDoubleSpace(Security::fixName($personalInfo['city'])));
            $curriculum->setPersonUF(strtoupper($personalInfo['uf']));
            $curriculum->setPersonBirthDate(Security::formatDate($personalInfo['birthdate']));
            $curriculum->setPersonDescription(Security::sanitizeString($personalInfo['description']));
            
            $personalContact = $params['personal_contact'];
            $curriculum->setPersonEmail($personalContact['email']);
            $curriculum->setPersonPhones($personalContact['phones']);
            $curriculum->setPersonSocialNetworks($personalContact['social_networks']);

            $personalEducation = $params['personal_education'];
            $curriculum->setPersonEducation($personalEducation);

            $personalSkills = $params['personal_skills'];
            $curriculum->setPersonSkills($personalSkills);

            $personalLangs = $params['personal_languages'];
            $curriculum->setPersonLanguages($personalLangs);

            $personalExperience = $params['personal_experience'];
            $curriculum->setPersonExperiences($personalExperience);

            $curriculum->insert($curriculum);
            return Http::getJsonReponseSuccess($response, [], 'Currículo Cadastrado Com Sucesso', Http::CREATED);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}