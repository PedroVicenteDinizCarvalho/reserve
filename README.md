# Projeto de Reserva de Mesas

Este projeto permite aos usuários visualizar as mesas disponíveis e fazer reservas em um sistema de restaurante. 
O sistema implementa restrições para garantir que as reservas sejam feitas dentro de horários permitidos e cuida para que mesas não sejam reservadas no mesmo período.

## Requisitos

Antes de executar o projeto, certifique-se de que tem as seguintes ferramentas instaladas:

- **PHP** 8.0 ou superior
- **Composer** (para gerenciamento de dependências do PHP)
- **Node.js** (para o front-end)
- **NPM** ou **Yarn** (para o gerenciamento de pacotes do front-end)
- **MySQL** (para banco de dados)
- **Docker** 24.0.5 ou superior
- **Docker Compose** 1.29.2 ou superior

## Passos para rodar o projeto

### 1. Clonar o repositório

Primeiro, clone o repositório do projeto em sua máquina local.

```bash
git clone https://github.com/PedroVicenteDinizCarvalho/reserve.git
cd reserve
```

### 2. Configure o arquivo .env

Crie um arquivo `.env` a partir do arquivo .env.example

```bash
cp .env.example .env
```

Edite o arquivo `.env` com as credencias do banco de dados MySQL:
OBS: Verificar as credenciais no arquivo docker-compose.yml

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_reserve
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### 3. Iniciar o Docker

```bash
docker-compose up --build
```

### 4. Rodar o composer dentro do Docker

Abra o terminal dentro do docker

```bash
docker-compose exec app bash
```
Instalação do composer

```bash
composer install
```

Comandos artisan

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```
### 5. Acessar app
http://localhost/

### 6. Desligar docker
```bash
docker-compose down
```