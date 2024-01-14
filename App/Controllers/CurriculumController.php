<?php

namespace App\Controllers;

use App\Models\CurriculumModel;
use App\Models\Http;
use App\Models\Security;
use Exception;
use InvalidArgumentException;
use Random\Engine\Secure;
use Slim\Http\Request;
use Slim\Http\Response;

class CurriculumController {
    private $model;

    /**Máximo  de currículos por pessoa */
    const MAX_CURRICULUM = 3;

    public function __construct() {
        $this->model = new CurriculumModel();
    }

    public function newCurriculum(Request $request, Response $response, array $args) : Response {
        $curriculum = $this->model;
        $params = $request->getParsedBody();

        try {

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
            $json = Http::obtainJsonSuccess('Currículo cadastrado com sucesso');

            $response->getBody()->write($json);
            
        } catch (InvalidArgumentException $error) {
            $response->getBody()->write(
                Http::obtainJsonError($error->getMessage())
            );
        } catch (Exception $error) {
            $response->getBody()->write(
                Http::obtainJsonError($error->getMessage())
            );
        }
        
        return $response;
    }
}