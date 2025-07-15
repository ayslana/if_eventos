# IF Eventos

> CRUD de eventos com usuários podendo participar dos mesmos

## Tabela de Conteúdos

- [Visão Geral](#visão-geral)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Rodando a Aplicação Localmente](#rodando-a-aplicação-localmente)
  - [Opção 1: Ambiente Local (Manual)](#opção-1-ambiente-local-manual)
  - [Opção 2: Com Docker](#opção-2-com-docker)
- [Integração Contínua (CI)](#integração-contínua-ci)
- [Entrega Contínua (CD)](#entrega-contínua-cd)
- [Monitoramento](#monitoramento)

## Visão Geral

(Seção opcional, mas recomendada)
Uma descrição mais detalhada da arquitetura do projeto ou de suas principais funcionalidades.

## Tecnologias Utilizadas

- **Backend:** Laravel, PHP 8.2
- **Frontend:** Blade, Vite, CSS, JavaScript
- **Banco de Dados:** SQLite (para testes de CI), PostgreSQL (para desenvolvimento/produção)
- **Containerização:** Docker, Docker Compose
- **Gerenciadores de Pacotes:** Composer, NPM
- **CI (Integração Contínua):** GitHub Actions
- **Code Style:** Laravel Pint

---

## Rodando a Aplicação Localmente

Você pode rodar o projeto de duas formas: configurando o ambiente manualmente na sua máquina ou utilizando Docker.

### Opção 1: Ambiente Local (Manual)

Siga os passos abaixo para configurar e executar o projeto diretamente no seu ambiente.

#### Pré-requisitos

- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) (versão 8.2 ou compatível)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/en/) (versão 20.x ou superior) e [NPM](https://www.npmjs.com/)
- Um servidor de banco de dados (PostgreSQL ou MySQL)

#### Instalação e Configuração

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/ayslana/if_eventos.git](https://github.com/ayslana/if_eventos.git)
    cd if_eventos
    ```

2.  **Instale as dependências do PHP e Node.js:**
    ```bash
    composer install
    npm install
    ```

3.  **Configure o arquivo de ambiente:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Ajuste o arquivo `.env`:**
    Abra o `.env` e configure as variáveis do seu banco de dados local (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

5.  **Execute as migrações do banco de dados:**
    ```bash
    php artisan migrate
    ```

#### Execução

1.  **Inicie o servidor do Laravel:**
    ```bash
    php artisan serve
    ```

2.  **Em um novo terminal, inicie o Vite:**
    ```bash
    npm run dev
    ```
A aplicação estará acessível em `http://127.0.0.1:8000`.

### Opção 2: Com Docker

Esta opção utiliza Docker para criar um ambiente de desenvolvimento containerizado, simplificando a configuração.

#### Pré-requisitos

- [Docker](https://www.docker.com/products/docker-desktop/)
- [Docker Compose](https://docs.docker.com/compose/install/) (geralmente já vem com o Docker Desktop)

#### Instalação e Configuração

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/ayslana/if_eventos.git](https://github.com/ayslana/if_eventos.git)
    cd if_eventos
    ```

2.  **Configure o arquivo de ambiente para o Docker:**
    Copie o arquivo de exemplo. **Não é necessário gerar a chave com `php artisan` agora.**
    ```bash
    cp .env.example .env
    ```
    Abra o arquivo `.env` e certifique-se de que as variáveis de banco de dados estão configuradas para se conectar ao container do Docker:
    ```
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=laravel # ou o valor que você definiu em docker-compose.yml
    DB_USERNAME=laraveluser # ou o valor que você definiu
    DB_PASSWORD=secret # ou o valor que você definiu
    ```

#### Execução

1.  **Construa e inicie os containers:**
    Este comando irá construir a imagem da aplicação e iniciar os serviços de `app` e `db` em background.
    ```bash
    docker-compose up -d --build
    ```

2.  **Gere a chave da aplicação e execute as migrações dentro do container:**
    ```bash
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate
    ```

A aplicação estará acessível em `http://localhost:8000`. Para parar os containers, use `docker-compose down`.

---

## Integração Contínua (CI)

Utilizamos **GitHub Actions** para automatizar a verificação de integridade do código a cada nova alteração, garantindo que o projeto permaneça estável e siga os padrões de qualidade. A pipeline de CI, definida em `.github/workflows/ci.yml`, executa uma série de verificações para validar o código:

- **Trigger:** É disparada em cada `push` para a branch `main` ou em cada `pull request` aberto para `main`.
- **Ambiente:** Configura um ambiente com PHP 8.2 e Node.js 20.
- **Verificações de Integridade:**
    - Instalação de Dependências (`composer install` e `npm install`).
    - Compilação de Assets (`npm run build`).
    - Configuração da Aplicação e migrações com SQLite.
    - Verificação de Estilo de Código com **Laravel Pint**.

## Entrega Contínua (CD)

A entrega contínua (CD) é facilitada pelo uso do Docker. O `Dockerfile` do projeto é otimizado para criar uma imagem de produção enxuta e segura.

### Visão Geral do Processo

1.  **Build da Imagem:** O `Dockerfile` utiliza um build multi-estágio. No primeiro estágio (`builder`), todas as dependências de desenvolvimento são instaladas, os assets são compilados e a aplicação é otimizada. No segundo estágio, apenas os artefatos finais e as dependências de produção são copiados, resultando em uma imagem final menor e mais segura.
2.  **Empacotamento:** A imagem Docker gerada contém a aplicação Laravel pronta para ser executada, com o servidor PHP-FPM configurado.
3.  **Implantação (Deploy):** A imagem pode ser enviada para um registro de contêineres (como Docker Hub, AWS ECR, ou GitHub Container Registry) e, a partir daí, implantada em qualquer ambiente de nuvem que suporte contêineres (ex: AWS, DigitalOcean, Heroku).

### Como fazer o deploy (Exemplo Manual)

1.  **Construir a imagem de produção:**
    ```bash
    docker build -t seu-usuario/if-eventos:latest .
    ```
2.  **Enviar para um registro (Ex: Docker Hub):**
    ```bash
    docker push seu-usuario/if-eventos:latest
    ```
3.  **No servidor de produção:**
    Você pode usar o `docker-compose.yml` (ajustado para produção) ou um orquestrador como Kubernetes para iniciar a aplicação a partir da imagem enviada.

## Monitoramento

Você pode acompanhar o status e os logs de execução da pipeline de CI na aba **"Actions"** do seu repositório no GitHub.

