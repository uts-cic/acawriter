<a href="https://snyk.io/test/github/uts-cic/acawriter?targetFile=package.json"><img src="https://snyk.io/test/github/uts-cic/acawriter/badge.svg?targetFile=package.json" alt="Known Vulnerabilities" data-canonical-src="https://snyk.io/test/github/uts-cic/acawriter?targetFile=package.json" style="max-width:100%;"></a>

# AcaWriter

<strong>AcaWriter</strong> was created by the Academic Writing Analytics project, at the UTS Connected Intelligence Centre. The software is now being shared and improved across universities in Australia and beyond, as part of the Higher Education Text Analytics open source project.

### AcaWriter requires TAP & Athanor installed.

## Tech Stack

Laravel, Postgres, GraphQL, Redis, node, socket.io, docker, docker-compose

## Installation

- Create a new EC2 instance - type medium
- Install Git
```sh
$ sudo yum install git
```
- Install the acawriter project
```sh
$ git clone  https://github.com/uts-cic/acawriter.git
```
- Install docker, docker-compose
```sh
$ sudo yum install -y docker
$ sudo usermod -aG docker ec2-user
$ sudo service docker start
$ sudo curl -L https://github.com/docker/compose/releases/download/1.21.0/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
```
Check docker-compose installation
```sh
$ docker-compose --version
docker-compose version 1.21.0, build 5920eb0
```
- Install laradock (ref: http://laradock.io)
    -- remove laradock empty folder from git repo
```sh
$ cd acawriter/
$ rm -rf laradock
$ git rm --cached -r laradock
```
-- Install laradock as a sub module
```sh
$ cd acawriter
$ git submodule add https://github.com/Laradock/laradock.git
```

## Setup

#### Laravel
```sh
$ cd acawriter/
$ cp .env.example .env
```
`sample .env`
```sh
		APP_NAME=AcaWriter
		APP_ENV=local
		APP_KEY=/** your key **/
		APP_DEBUG=true
		APP_LOG_LEVEL=debug
		APP_URL=/** your url **/

		DB_CONNECTION=pgsql
		DB_HOST=postgres
		DB_PORT=5432
		DB_DATABASE=default /** must match laradock psql setting **/
		DB_USERNAME=default /** must match laradock psql setting **/
		DB_PASSWORD=secret /** must match laradock psql setting  **/

		BROADCAST_DRIVER=redis
		CACHE_DRIVER=redis
		SESSION_DRIVER=redis
		QUEUE_DRIVER=redis

		REDIS_HOST=redis
		REDIS_PASSWORD=null
		REDIS_PORT=6379

		MAIL_DRIVER=smtp
		MAIL_HOST= /**  your host name (works with AWS SES) **/
		MAIL_PORT=587
		MAIL_USERNAME=/** username **/
		MAIL_PASSWORD=/** password **/
		MAIL_ENCRYPTION=tls
		MAIL_FROM_ADDRESS=/** from email **/
		MAIL_FROM_NAME=/** from name **/

		PUSHER_APP_ID=
		PUSHER_APP_KEY=
		PUSHER_APP_SECRET=

		/** 3 options below are for AAF integration **/
		AAF_SECRET=
		AAF_AUD=
		AAF_LINK=

		TAP_API=/** tap URL to query **/
		MIX_APP_SOCKET= /** socket.io url & port **/
```

#### Laradock
```sh
$ cp laradock/env-example laradock/.env
```
`update laradock/.env to enable redis, php-fpm, php-worker, postgres, psql, node
ensure following are set to true`
```sh
    PHP_VERSION=71
    WORKSPACE_INSTALL_PYTHON = true
    WORKSPACE_INSTALL_PHPREDIS=true
    WORKSPACE_INSTALL_NODE=true
    WORKSPACE_INSTALL_YARN=true
    PHP_WORKER_INSTALL_PGSQL=true

```

#### AAF Settings

- Update the following values in .env
- Production link will need SSL
- Redirect path for AAF : https://your-acawriter-url/auth/jwt
[refer] https://github.com/uts-cic/acawriter/blob/dfd164f1524c055ebddecf6df5530fd62172e1f9/routes/web.php#L38 should you wish to update the link

```sh
AAF_SECRET=
AAF_AUD=
AAF_LINK=
```

#### docker build/run
```sh
$ docker-compose up -d nginx php-fpm postgres redis workspace php-worker
```

If all goes well running the following command
```sh
$ docker-compose ps
```

| Name  |                 Command   |            State    |                Ports|
|------ | ------ |------ | ------ |
laradock_nginx_1  |      nginx           |                 Up  |    0.0.0.0:443->443/tcp, 0.0.0.0:80->80/tcp |
|laradock_php-fpm_1 |      docker-php-entrypoint php-fpm |   Up |     9000/tcp
|laradock_php-worker_1  | /usr/bin/supervisord -n -c ... |  Up|
|laradock_postgres_1   |  docker-entrypoint.sh postgres  |  Up   |   0.0.0.0:5432->5432/tcp
|laradock_redis_1   |     docker-entrypoint.sh redis ... |  Up   |  0.0.0.0:6379->6379/tcp
|laradock_workspace_1 |   /sbin/my_init           |         Up   |   0.0.0.0:2222->22/tcp


#### Using workspace to Run compose/npm commands
```sh
$ cd acawriter/laradock/
$ docker docker-compose exec workspace bash
```

#### Setup acawriter

Login to workspace (should take to the root /var/www)

```sh
$ composer install
$ npm install
$ php artisan migrate
$ php artisan db:seed
```

** npm install - errors refer known issues section.


For production environments...

```sh
$ npm run prod
$ ./startup.sh /* runs the node socket.js for webscokets to work */
```

## Access control/Admin
By default if AAF used for login, Acawriter will identify and allocate roles resp. as user (for students) and staff(staff) logins. However to create a super admin, you would need to manually login and set them up. To do so login to postgres db using the following
```sh
docker-compose exec postgres psql -U default -W -d default
```
and add superadmin (role=1) into user_role table

```sh
insert into user_role (user_id, role_id) values (1,1);
```

Super admin will allow for managing users as of now. (Other super admin feature & updates to follow)




## Known Issues

* Error with the python version
* npm install - node-sass module requires python ver 2.7.* (only) ensure that this is loaded checking the workspace.
* Unable to save binary /var/www/node_modules/node-sass/vendor/linux-x64-59
* node-sass@4.7.2 postinstall /var/www/node_modules/node-sass
#### Possible solutions
The following would need to be installed into the docker workspace container, cmd to login to container
```sh
$ docker docker-compose exec workspace bash
```
* npm install --unsafe-perm node-sass
* npm install node-gyp


## Run production settings

* Ensure adding appropriate load balancers for socket.io port 3000


## License
----

AcaWriter is open sourced under [Apache 2.0] licence.
