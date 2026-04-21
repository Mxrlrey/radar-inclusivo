# Radar Inclusivo

Sistema web para gestão de acessibilidade, inclusão educacional e apoio operacional ao AEE. O projeto centraliza cadastros, empréstimos, barreiras, agenda institucional, backups, relatórios e notificações em uma única aplicação Laravel.

Foi desenvolvido como projeto acadêmico no Instituto Federal Baiano, com foco em organizar processos que normalmente ficam dispersos entre planilhas, registros manuais e controles isolados.

## Visão geral

O Radar Inclusivo reúne dois eixos que se complementam:

- Gestão administrativa e operacional de inclusão
- Apoio ao Atendimento Educacional Especializado (AEE)

Na prática, a plataforma conecta alunos, profissionais, materiais acessíveis, tecnologias assistivas, barreiras de acessibilidade, eventos institucionais e rotinas de acompanhamento em um ambiente web responsivo.

## Funcionalidades

Com base na página [about-us.blade.php](/home/marley/Projetos/radar-inclusivo/resources/views/pages/about-us.blade.php) e nos módulos existentes no projeto, o sistema hoje cobre:

- `Dashboard`: visão geral com indicadores, atalhos e panorama rápido do sistema.
- `Relatórios`: geração, consolidação e exportação de dados para acompanhamento gerencial.
- `Notificações`: avisos do sistema, eventos, alertas e acompanhamento de leitura.
- `Backups`: criação, upload, download, sincronização e restauração de cópias de segurança.
- `Deficiências`: cadastro dos tipos de deficiência vinculados ao contexto de atendimento.
- `Cargos`: definição de funções e permissões de profissionais.
- `Recursos de Acessibilidade`: catálogo de recursos como braille, Libras e audiodescrição.
- `Categorias de Barreiras`: classificação de barreiras físicas, comunicacionais, atitudinais e correlatas.
- `Instituições`: cadastro da instituição base e suas informações geográficas.
- `Localizações`: mapeamento de espaços físicos e pontos de referência.
- `Alunos`: gestão do cadastro de estudantes atendidos.
- `Equipe`: gestão de profissionais e equipe multiprofissional.
- `Tecnologias Assistivas`: controle de equipamentos e recursos tecnológicos de apoio.
- `Materiais Pedagógicos Acessíveis`: cadastro e controle de materiais adaptados.
- `Barreiras`: registro, acompanhamento e inspeção de problemas de acessibilidade.
- `Empréstimos`: controle de saída, devolução e acompanhamento de itens emprestados.
- `Fila de Espera`: gestão de demanda reprimida por recursos e materiais.
- `Agenda Institucional`: eventos, lembretes e organização de atividades da instituição.

Além disso, o projeto também possui:

- `Inspeções` vinculadas a barreiras, materiais e tecnologias assistivas
- `Logs/Auditoria` para rastreabilidade de alterações
- `Perfil do usuário`
- `Modo escuro`, `alto contraste` e integração com `VLibras`
- `Exportações em PDF`
- `Scheduler` para rotinas automáticas de backup, lembretes e verificações

## Stack atual

| Camada | Tecnologia |
| --- | --- |
| Backend | `PHP 8.2` |
| Framework | `Laravel 12` |
| Frontend build | `Node 20` + `Vite 7` |
| UI base | `Bootstrap 5.3` |
| Banco | `MySQL 8.0` |
| Runtime web | `Nginx` + `PHP-FPM` |
| Containers | `Docker` + `Docker Compose` |

Principais pacotes do projeto:

- `barryvdh/laravel-dompdf`
- `intervention/image`
- `spatie/laravel-backup`
- `laravel/tinker`

## Estrutura principal

Alguns diretórios importantes:

- `app/Http/Controllers`: controladores dos módulos
- `resources/views/pages`: telas e páginas da aplicação
- `resources/css` e `resources/js`: frontend do sistema
- `routes/modules.php`: rotas dos módulos de negócio
- `routes/console.php`: rotinas agendadas
- `docker-compose.dev.yml`: ambiente de desenvolvimento
- `Dockerfile`: imagem base da aplicação
- `docker/php/entrypoint.dev.sh`: bootstrap do container no ambiente dev

## Como rodar o projeto

### Pré-requisitos

- `Docker`
- `Docker Compose`

Opcional:

- `make`, para usar os atalhos do `Makefile`

### Fluxo recomendado

1. Garanta que o arquivo `.env` exista na raiz.
2. Suba o ambiente de desenvolvimento.
3. Acesse a aplicação em `http://localhost:8080`.

Com `make`:

```bash
make down-v
make build
make up
```

Ou direto com Docker Compose:

```bash
docker compose -f docker-compose.dev.yml down -v
docker compose -f docker-compose.dev.yml build --no-cache
docker compose -f docker-compose.dev.yml up
```

### Endereços locais

- Aplicação: `http://localhost:8080`
- Vite dev server: `http://localhost:5173`
- phpMyAdmin: `http://localhost:8081`
- MySQL host local: `127.0.0.1:3307`

## Como o ambiente Docker dev funciona

O arquivo [docker-compose.dev.yml](/home/marley/Projetos/radar-inclusivo/docker-compose.dev.yml) sobe 5 serviços:

- `app`: container principal do Laravel com `PHP-FPM`
- `scheduler`: executa `php artisan schedule:work`
- `db`: MySQL 8.0
- `nginx`: servidor web e proxy para o PHP-FPM
- `node`: Vite em modo dev

### Serviço `app`

O `app` usa o [Dockerfile](/home/marley/Projetos/radar-inclusivo/Dockerfile) e inicia com o script [entrypoint.dev.sh](/home/marley/Projetos/radar-inclusivo/docker/php/entrypoint.dev.sh). No dev ele:

- cria diretórios de `storage` e cache
- instala dependências PHP se `vendor/` ainda não existir
- roda `php artisan package:discover`
- cria `storage:link`
- executa migrations
- só então inicia o `php-fpm`

Isso resolve o problema clássico de subir o projeto sem `vendor`, sem cache e sem banco preparado.

### Serviço `scheduler`

O `scheduler` usa a mesma imagem do `app`, mas roda apenas:

```bash
php artisan schedule:work
```

Hoje ele sustenta tarefas automáticas como:

- limpeza e sincronização de backups
- backup diário
- verificação de empréstimos em atraso
- envio de lembretes de eventos institucionais

### Serviço `db`

O MySQL usa:

- imagem `mysql:8.0`
- volume persistente `dbdata`
- configuração de dev em [docker/mysql/my.dev.cnf](/home/marley/Projetos/radar-inclusivo/docker/mysql/my.dev.cnf)

Ele expõe a porta:

```txt
3307 -> 3306
```

### Serviço `nginx`

O `nginx` serve a pasta `public/` e encaminha PHP para o `app:9000`.

Importante: ele só sobe depois que o `app` fica saudável, evitando `502` durante o bootstrap inicial do Laravel.

### Serviço `node`

O `node` sobe o Vite em modo desenvolvimento e usa volume dedicado para `node_modules`. Ele roda:

```bash
if [ ! -d node_modules/vite ]; then npm ci; fi && npm run dev -- --host 0.0.0.0
```

Assim o frontend não reinstala dependências toda vez sem necessidade.

## Como o Dockerfile foi estruturado

O [Dockerfile](/home/marley/Projetos/radar-inclusivo/Dockerfile) foi dividido em estágios para reduzir acoplamento e deixar a imagem final mais coerente com o runtime.

### 1. Estágio `php_builder`

Objetivo: preparar o ecossistema PHP e instalar dependências Composer.

O que ele faz:

- instala dependências de compilação via `apk add`
- compila extensões PHP usadas pelo projeto:
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
- instala `redis` via `pecl`
- copia o binário do `composer`
- copia `composer.json` e `composer.lock`
- roda `composer install --no-scripts --no-autoloader`

Por que assim:

- evita compilar extensões na imagem final toda vez
- reaproveita melhor cache de camadas
- mantém `vendor/` pronto para ser copiado depois

### 2. Estágio `node_builder`

Objetivo: gerar os assets do frontend.

O que ele faz:

- copia `package.json` e `package-lock.json`
- roda `npm ci`
- copia `resources`, `public` e `vite.config.js`
- roda `npm run build`

Por que assim:

- o build de frontend fica isolado
- a imagem final recebe apenas `public/build`
- evita misturar runtime PHP com toolchain Node no container final

### 3. Estágio final `php:8.2-fpm-alpine`

Objetivo: montar o runtime enxuto da aplicação.

O que ele faz:

- instala bibliotecas de runtime
- instala `mysql-client`, necessário para backup/restauração
- ajusta timezone
- copia extensões compiladas e arquivos `.ini` vindos do `php_builder`
- copia `composer`
- ajusta UID/GID do `www-data`
- copia `vendor/`
- copia o código do projeto
- copia `public/build` gerado no estágio Node
- cria diretórios de backup e cache
- ajusta permissões de `storage` e `bootstrap/cache`
- roda:
  - `composer dump-autoload --optimize --no-scripts`
  - `php artisan package:discover --ansi`

Por que esse desenho foi adotado:

- a imagem final já sai pronta para executar Laravel
- o runtime fica com os binários realmente necessários
- backup/restauração continuam funcionando porque `mysql-client` está presente
- a construção não depende de Node no container final

## Modelo de `.env`

Abaixo está um modelo baseado na `.env` atual do projeto, mas sanitizado para documentação.

```env
APP_NAME=RadarInclusivo
APP_ENV=local
APP_KEY=base64:SUA_CHAVE_AQUI
APP_DEBUG=true
APP_URL=http://localhost:8080

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

OSM_API_URL=https://nominatim.openstreetmap.org/search
OSM_USER_AGENT="Radar Inclusivo - contato@radarinclusivo.exemplo.com"

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=radar_db
DB_USERNAME=radar_user
DB_PASSWORD=radar_senha_segura

BACKUP_DISK_NAME=GNAIbackups
BACKUP_PATH=storage/app/private/GNAIbackups
BACKUP_MYSQL_BINARY_PATH=/usr/bin
BACKUP_MYSQL_EXTRA_OPTIONS="--protocol=tcp --skip-ssl"
BACKUP_ARCHIVE_PASSWORD=
BACKUP_MAIL_TO=backup@radarinclusivo.exemplo.com

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=database
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1

MAIL_MAILER=smtp
MAIL_HOST=smtp.exemplo.com
MAIL_PORT=587
MAIL_USERNAME=nao-responda@radarinclusivo.exemplo.com
MAIL_PASSWORD=sua_senha_smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=nao-responda@radarinclusivo.exemplo.com
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## Módulos, rotas e rotina automática

Os módulos principais estão organizados em [routes/modules.php](/home/marley/Projetos/radar-inclusivo/routes/modules.php). Entre eles:

- pessoas
- deficiências
- cargos
- estudantes
- profissionais
- tecnologias assistivas
- materiais pedagógicos acessíveis
- barreiras
- categorias de barreiras
- instituições
- localizações
- recursos de acessibilidade
- agenda institucional
- empréstimos
- filas de espera
- backups
- notificações
- relatórios

As rotinas automáticas vivem em [routes/console.php](/home/marley/Projetos/radar-inclusivo/routes/console.php), incluindo:

- `backup:clean`
- `backup:run`
- `loans:check-overdue`
- `inclusive-radar:send-event-reminders`

## Recursos de interface e acessibilidade

Pelo layout atual em [master.blade.php](/home/marley/Projetos/radar-inclusivo/resources/views/layouts/master.blade.php), a aplicação já integra:

- `Bootstrap 5`
- `Select2`
- `Font Awesome`
- `Ionicons`
- `CKEditor 5`
- `VLibras`
- tema escuro
- alto contraste

## Comandos úteis

Alguns comandos do fluxo atual:

```bash
make build
make up
make down
make down-v
make logs
make logs-app
make art migrate
make art tinker
make npm-build
```

Se preferir, você também pode usar os comandos diretos do `docker compose`.

## Observações importantes

- O bootstrap inicial do `app` roda migrations automaticamente no dev.
- O `nginx` espera o `app` ficar saudável antes de subir.
- O projeto depende de `mysql-client` dentro do container PHP para backup e restauração.
- O volume `dbdata` persiste o banco, e o volume `node_modules` evita reinstalação completa a cada subida do Vite.

## Créditos

- Desenvolvimento: Marley Teixeira Meira
- Orientação: Prof. Woquiton Fernandes
- Instituição: Instituto Federal Baiano — Campus Guanambi
- Curso: Tecnologia em Análise e Desenvolvimento de Sistemas
- Projeto acadêmico: Trabalho de Conclusão de Curso
