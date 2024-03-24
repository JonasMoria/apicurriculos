# API-Curriculos v1.0
API desenvolvida para servir de repositório de currículos. Tem como objetivo armazenar e consultar currículos em sua base da dados.

## Dependências:
- Slim Framework v3.0
- PHP 7.4 ou superior
- tuupola/slim-jwt-auth

## Instruções:
* As requisições são realizadas com base nos protocolos POST, PUT, GET, DELETE utilizando dados no formato JSON.
* Sistema de autenticação realizado via token JWT
  
### Cadastro de Perfil:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/register | - |

| dado | Tipo | Obrigatório |
| --- | --- | --- |
| nome | String | Sim |
| email | String | Sim |
| password | String | Sim |

```yaml
{
    "name" : "Nome Usuario Completo",
    "email" : "usuario@email.com",
    "password" : "Senha123"
}
```

### Login
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/login | -

```yaml
{
    "email" : "usuario@email.com",
    "pass":"senha_em_sha512"
}
```

### Visualizar Perfil:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| GET | /perfil/view | Autenticação Requerida

### Editar Perfil:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| PUT | /perfil/update | Autenticação Requerida

| dado | Tipo | Obrigatório |
| --- | --- | --- |
| user_nome | String | Não |
| user_email | String | Não |
| user_password | String | Não |

```yaml
{
    "user_name" : "Nome Usuario Completo",
    "user_email" : "usuario@email.com",
    "user_password" : "Senha123"
}
```

### Deletar Perfil:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| DELETE | /perfil/delete | Autenticação Requerida

### Cadastrar Currículo:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /curriculum/new | Autenticação Requerida

| dado | Tipo | Obrigatório |
| --- | --- | --- |
| curriculum_name | String | Sim |
| personal_info | Array | Sim |
| name | String | Sim |
| city | String | Sim |
| uf | String | Sim |
| birthdate | Date | Sim |
| description | String | Não |
| personal_contact | Array | Sim |
| email | String | Sim |
| phones | Array | Sim |
| principal | String | Sim |
| secondary | String | Não |
| social_networks | Array | Não |
| linkedIn | String | Não |
| facebook | String | Não |
| instagram | String | Não |
| site | String | Não |
| personal_education | Array | Não |
| *Sigla_Curso | Array Key | Sim |
| course_name | String | Sim |
| course_modality | String | Sim |
| institution | String | Sim |
| status | Int | Sim |
| init | Date | Sim |
| finish | Date | Sim |
| personal_skills | Array | Não |
| personal_languages | Array | Não |
| personal_experience | Array | Não |
| *Nome_Empresa | Array Key | Sim |
| office | String | Sim |
| init | Date | Sim |
| finish | Date | Sim |
| activities | String | Não |

```yaml
{
    "curriculum_name" : "Currículo Fulano",
    "personal_info": {
        "name": "Fulano da Silva",
        "city": "São Paulo",
        "uf" : "SP",
        "birthdate": "2002-10-10",
        "description" : "descrição desejada"
    },
    "personal_contact" : {
        "email": "emaildecontato@gmail.com",
        "phones" : {
            "principal" : "5511999999999",
            "secondary" : "551199999999"
        },
        "social_networks" : {
            "linkedIn" : "https://linkedin.com",
            "facebook" : "https://facebook.com.br/fulano",
            "instagram" : "https://instagram.com.br/fulano",
            "site" : "https://fulano.com.br"
        }
    },
    "personal_education" : {
        "ADS" : {
            "course_name" : "Analise e Desenvolvimento de Sistemas",
            "course_modality" : "Tecnólogo",
            "institution" : "FATEC",
            "status" : "1", // [0 - Trancado][1 - em andamento][2 - concluído]
            "init" : "2020-02-10",
            "finish" : "0000-00-00"
        }
    },
    "personal_skills" : [
        "Adaptabilidade", "Aprendizado", "foco"
    ],
    "personal_languages" : [
        "Inglês", "Espanhol", "Alemão"
    ],
    "personal_experience" : {
        "Nome Empresa" : {
            "office" : "Supervisor",
            "init" : "2022-02-10",
            "finish" : "0000-00-00", // 0000-00-00 se for emprego atual
            "activities" : "Supervisão e coordenação"
        }
    }
}
```

### Visualizar Currículos do Perfil:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| GET | /curriculum/list | Autenticação Requerida

### Visualizar um Currículo em Específico:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| GET | /curriculum/view/id_do_curriculo | Autenticação Requerida

### Atualizar Currículo:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| PUT | /curriculum/update/id_do_curriculo | Autenticação Requerida

- Obs: Envie à API apenas os campos em que deseja atualizar. Atenção aos campos 'personal_education' e 'personal_experience' pois possuem campos diferentes aos campos da requisição de cadastro!
```yaml
{
    "curriculum_name" : "Currículo Fulano v2",
    "oficial": 1, // [0 - Não oficial] [1 - Oficial]
    "personal_info": {
        "name": "Fulano Oliveira",
        "city": "São Paulo",
        "uf" : "SP",
        "birthdate": "2002-10-10",
        "description" : "Descrição do curriculo"
    },
    "personal_contact" : {
        "email": "fulano@email.com",
        "phones" : {
            "principal" : "5512999999999",
            "secondary" : "551299999999"
        },
        "social_networks" : {
            "linkedIn" : "https://linkedin.com",
            "facebook" : "https://facebook.com.br/fulano",
            "instagram" : "https://instagram.com.br/fulano",
            "site" : "https://fulano.com.br"
        }
    },
    "personal_education" : [
        {
            "id": 1,
            "course_acronym" : "ADS",
            "course_name" : "Analise de Sistemas",
            "course_modality" : "Tecnólogo",
            "institution" : "FATEC",
            "status" : "2",
            "init" : "2020-02-10",
            "finish" : "2022-12-15"
        },
        {
            "id": 2,
            "course_acronym" : "ADM",
            "course_name" : "Administração",
            "course_modality" : "Técnico",
            "institution" : "ETEC",
            "status" : "0",
            "init" : "2019-02-10",
            "finish" : "2019-12-10"
        }
    ],
    "personal_skills" : [
        "Adaptabilidade", "Aprendizado", "foco"
    ],
    "personal_languages" : [
        "Inglês", "Espanhol", "Alemão"
    ],
    "personal_experience" : [
        {
            "id" : 1,
            "enterprise": "Nome Empresa",
            "office" : "Gerente",
            "init" : "2022-02-10",
            "finish" : "0000-00-00",
            "activities" : "gerencia ao setor"
        }
    ]
}
```
### Deletar Currículo:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| DELETE | /curriculum/delete/id_do_curriculo | Autenticação Requerida

### Mecanismo de Pesquisa de Currículos:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/search | Envie apenas os parâmetros que deseja filtrar na busca

```yaml
{
    "person_cities" : ["São José dos Campos", "São Paulo"],
    "person_uf" : ["SP", "SC"],
    "education_acronym" : ["ADM", "ADS"],
    "education_modality" : ["Tecnólogo", "phd"],
    "education_institution" : ["UFRJ", "USP"],
    "education_status" : [1, 2],
    "experience_enterprise" : ["Google", "Facebook"],
    "experience_office" : ["Estagiário", "Desenvolvedor"],
    "person_langs" : ["inglês", "Espanhol"],
    "person_skills" : ["Foco", "Disciplina"]
}
```

### Mecanismo de Visualização de Currículo:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| GET | /api/search/id_do_curriculo | -
  




  
