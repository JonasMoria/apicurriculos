<?php

namespace App\Models;

class AppModel {
    const APP_VERSION = 'v0.1 beta';

    /**
     * Obtém os dados da aplicação para fins informativos
     * 
     * @return array Informações do App
     */
    public function getAboutApp() {
        $infoApp = [];

        $infoApp['project_name'] = 'API Curriculos';
        $infoApp['version'] = self::APP_VERSION;
        $infoApp['description'] = self::getAppDescription();

        $infoApp['routes'] = [
            'app_informations' => '/',
        ];

        $infoApp['dev_owner'] = 'Jonas Moriá';
        $infoApp['dev_email'] = 'moriajonas@gmail.com';
        $infoApp['dev_github'] = 'https://github.com/JonasMoria';

        return $infoApp;
    }

    private static function getAppDescription() {
        $description = "
            API com o objetivo de reunir currículos e empresas interessadas em contratar pessoas.
            Com a API é possível buscar e filtrar por currículos com base em informações como, por exemplo, formações, experiências, cursos
            entre outros pontos chaves.
            Além disso, é possível que empresas cadastrem suas vagas e que pessoas encontrem vagas desejadas conforme filtros desejados, por exemplo,
            faixa de remuneração, tipo de vaga, conhecimentos entre outros.
        ";

        return $description;
    }
}