# AcaWriter

<strong>AcaWriter</strong> was created by the Academic Writing Analytics project, at the UTS Connected Intelligence Centre. The software is now being shared and improved across universities in Australia and beyond, as part of the Higher Education Text Analytics open source project.

## Tech Stack

Laravel, Postgres, GraphQL, Redis, node, socket.io, docker, docker-compose

## Requirements

At least 8GB of free RAM is required, recommended 16GB.

Install the following software in your environment:

-   <a href="https://git-scm.com/book/en/v2/Getting-Started-Installing-Git">GIT</a>
-   <a href="https://docs.docker.com/install/">Docker</a>
-   <a href="https://docs.docker.com/compose/install/">Docker Compose</a>

Check the following:

```sh
$ docker --version
Docker version XX.XX.XX, build XXXXXXX

$ docker-compose --version
docker-compose version X.XX.X, build XXXXXXXX

$ git --version
git version X.XX.X
```

## Code checkout

```sh
git clone https://github.com/uts-cic/acawriter.git
cd acawriter
```

## Environment configuration

At the minimum you need to configure APP_KEY, and APP_URL (if not running on localhost):

```sh
cp .env.example .env
vim .env

...
APP_KEY=base64:mTC7uqwOB0YpGbMZSueR/zC4pYE9mDheXqnm3NFQ0MQ=
...
APP_URL=http://my-server-url /* keep http://localhost setting for local setup*/
...
DB_PASSWORD=my-secure-password
...
```

### APP_KEY

To generate APP_KEY you can use:

```sh
$ openssl rand -base64 32
WhkENO8c0jB0kWcrqIsFgsdl+AQqs9XZg5C+UYEE8FI=
```

Prepend `base64:`

OR

```sh
$ docker-compose exec app php artisan key:generate --show
base64:yq2h/9XOHYiRWjT5QsTha8HhP3MlmFEH7E3tWsZyiXw=
```

### Okta Settings

-   Redirect path for AAF : https://your-acawriter-url/auth/okta
-   Update the following values in .env

```sh
OKTA_ISSUER=
OKTA_CLIENT_ID=
OKTA_CLIENT_SECRET=
```

### AAF Settings

-   Production link will need SSL
-   Redirect path for AAF : https://your-acawriter-url/auth/jwt
-   Update the following values in .env

```sh
AAF_SECRET=
AAF_AUD=
AAF_LINK=
```

### LTI Integration

AcaWriter supports LTI integration.

-   LTI callback URL: https://your-acawriter-url/auth/lti
-   Update the following value in .env

```sh
LTI_KEY=
LTI_SECRET=
```

## Build and run docker containers

```sh
$ docker-compose up -d
```

Once build process is completed, you can check if the containers are running:

```sh
$ docker-compose ps

  Name                Command               State               Ports
----------------------------------------------------------------------------------
app        docker-php-entrypoint php-fpm    Up      9000/tcp
athanor    /opt/docker/bin/athanor-server   Up      0.0.0.0:8083->8083/tcp
nginx      nginx -g daemon off;             Up      0.0.0.0:80->80/tcp
postgres   docker-entrypoint.sh postgres    Up      5432/tcp
redis      docker-entrypoint.sh redis ...   Up      6379/tcp
socketio   docker-entrypoint.sh node  ...   Up      3000/tcp
tap        /opt/docker/bin/tap              Up      0.0.0.0:9000->9000/tcp
```

## Initial setup

Run the following commands to:

-   Setup database
-   Populate database with roles and fatures
-   Create the first user admin account - follow the prompts, and select admin role (4)

```sh
$ docker-compose exec app php artisan migrate
$ docker-compose exec app php artisan db:seed
$ docker-compose exec app php artisan create:user
```

## License

AcaWriter is open sourced under [Apache 2.0] licence.
