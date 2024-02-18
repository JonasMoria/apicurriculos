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

    public function delete(Request $request, Response $response, array $args) : Response {
        $curriculum = $this->model;
        $params = $request->getParsedBody();
        $userID = $_SESSION['user_id'];
        $curriculumID = Security::filterInt($args['id']);

        try {
           if (empty($curriculumID)) {
                throw new InvalidParamException('Currículo não identificado');
           }

           $curriculum->disable($userID, $curriculumID);

           return Http::getJsonReponseSuccess($response, [], 'Currículo Deletado Com Sucesso', Http::OK);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
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
            if (empty($curriculumID)) {
                throw new InvalidParamException('Currículo não identificado');
            }

            $fieldsToUpdate = [];
            if (!empty($params['curriculum_name'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdateCvName($params['curriculum_name']));
            }

            if (isset($params['oficial']) && in_array($params['oficial'], [0, 1])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayCvOfficial($params['oficial']));
            }

            if (!empty($params['personal_info'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdatePersonalInfo($params['personal_info']));
            }

            if (!empty($params['personal_contact'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdatePersonalContact($params['personal_contact']));
            }

            if (!empty($params['personal_education'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdatePersonalEducation($params['personal_education']));
            }

            if (!empty($params['personal_experience'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdatePersonalExperience($params['personal_experience']));
            }

            if (!empty($params['personal_skills'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdatePersonalSkills($params['personal_skills']));
            }

            if (!empty($params['personal_languages'])) {
                $fieldsToUpdate += (CurriculumModel::makeArrayUpdatePersonalLangs($params['personal_languages']));
            }

            $curriculum->update($userID, $curriculumID, $fieldsToUpdate);

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