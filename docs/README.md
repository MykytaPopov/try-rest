Configure the hosts file
```bash
$ sudo nano /etc/hosts
```
Add new line
```bash
127.0.0.1 try-rest.local
```
Go to the project folder

Create .env configs file
```bash
$ cp .env.example .env
```
Go to docker folder
```bash
$ cd docker
```
Start the project environment
```bash
$ docker-compose up
```
In the new terminal window get in to the container as non root user run
```bash
$ docker exec -it try_rest_php_1 su dev
```
Install dependencies
```bash
$ composer install
```
Execute migrations
```bash
$ ./bin/console doctrine:migrations:migrate --no-interaction
```
Also custom bundle has unit tests to run them
Go to local bundle folder
```bash
$ cd bundles/ClassroomBundle
```
Install dependencies for bundle
```bash
$ composer install
```
Run tests
```bash
$ ./vendor/bin/simple-phpunit
```
