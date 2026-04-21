# Checklist de Runtime para Docker

Este projeto ja tem `Dockerfile` e `docker-compose`, mas o runtime correto depende de alguns detalhes do Laravel, do Vite e, principalmente, do fluxo de backup/restauracao.

## Versoes reais do projeto

- PHP: `8.2` (`composer.json` exige `^8.2`)
- Laravel: `12.x`
- Node: `20`
- Vite: `7.0.7`
- Bootstrap: `5.3.3`
- MySQL: `8.0`
- Redis: opcional no codigo atual, mas previsto no compose de producao

## Dependencias PHP obrigatorias

Estas extensoes precisam existir na imagem PHP:

- `bcmath`
- `exif`
- `gd`
- `intl`
- `mbstring`
- `opcache`
- `pcntl`
- `pdo_mysql`
- `xml`
- `zip`
- `redis` via PECL

Motivos principais:

- `laravel/framework` precisa de `mbstring` e `xml`
- `intervention/image` usa `gd`
- `spatie/laravel-backup` usa `zip`
- restauracao de backup usa `ZipArchive`
- Redis ja esta previsto em `config/database.php`

## Pacotes de sistema obrigatorios

- `mysql-client`
- `git`
- `unzip`
- `bash`
- libs de runtime de `gd`, `intl`, `xml`, `zip`, `mbstring`

`mysql-client` e obrigatorio porque o `BackupService` restaura SQL chamando o binario `mysql`, e o Spatie usa `mysqldump` no backup.

## Build de frontend

Se a imagem for usada em producao sem bind mount, ela precisa gerar:

- `public/build`

Por isso o `Dockerfile` atual usa um estagio com `Node 20` e roda:

```bash
npm ci
npm run build
```

## Variaveis de ambiente minimas

Use `.env.example` como base. As variaveis mais importantes para Docker sao:

- `APP_ENV`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `FILESYSTEM_DISK`
- `SESSION_DRIVER`
- `CACHE_STORE`
- `QUEUE_CONNECTION`
- `BACKUP_DISK_NAME`
- `BACKUP_MYSQL_BINARY_PATH`
- `BACKUP_MYSQL_EXTRA_OPTIONS`

## Requisitos especificos de backup/restauracao

O modulo de backup depende destes comportamentos:

- gera ZIP em `storage/app/private/GNAIbackups`
- usa `storage/app/backup-temp` como temporario
- usa `mysql` no restore
- usa `mysqldump` no backup automatico/manual
- usa `cp -R` e `rm -rf` no Linux

Por isso, a imagem precisa:

- permissao de escrita em `storage` e `bootstrap/cache`
- `mysql-client` no PATH
- `BACKUP_MYSQL_BINARY_PATH=/usr/bin`

## Volumes que nao podem conflitar

Se voce separar app e banco em containers:

- banco: volume dedicado do MySQL
- app: persistir ao menos `storage/`

Se quiser preservar backups entre deploys, monte explicitamente:

```txt
storage/app/private/GNAIbackups
```

## Servicos de runtime do projeto

Minimo:

- `app` PHP-FPM
- `nginx`
- `db` MySQL

Para operacao completa:

- `scheduler` para `php artisan schedule:work`
- `queue` se for usar worker dedicado
- `node` apenas em dev com `vite`
- `redis` apenas se cache/fila/sessao forem movidos para Redis

## Migrations e tabelas de infraestrutura

O container precisa rodar migrations que criam:

- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `sessions`
- `notifications`
- `backups`

Sem isso, o projeto quebra mesmo antes das regras de negocio.

## Inconsistencias importantes encontradas

- `README.md` dizia `PHP 8.4`, mas o projeto real esta em `PHP 8.2`
- nao havia `.env.example`, mas o `composer.json` pressupoe esse arquivo em alguns scripts
- backup/restauracao exigem binarios de MySQL dentro do container PHP, nao apenas no container do banco

## Validacao minima depois do build

```bash
php -m | grep -E "mbstring|xml|zip|gd|intl|pdo_mysql|redis"
php artisan about
php artisan migrate --force
php artisan storage:link --force
php artisan backup:run
php artisan backup:clean
```

Se esses comandos passarem, a base do Docker esta coerente com o projeto.
