<?php

namespace App\DAO;

class CurriculumDAO {
    private $database;

    const TABLE_CV = 'users_curriculum';
    const TABLE_PI = 'users_personal_infos';
    const TABLE_PC = 'users_personal_contacts';
    const TABLE_PE = 'users_personal_educations';
    const TABLE_PEX = 'users_personal_experiences';
    const TABLE_PL = 'users_personal_langs';
    const TABLE_PS = 'users_personal_skills';

    public function __construct() {
        $this->database = new Database();
    }

    public function createCurriculum(array $data) {
        $dbase = $this->database;

        $idCurriculum = $dbase->insertWithArray(self::TABLE_CV, $data);

        return $idCurriculum;
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


}