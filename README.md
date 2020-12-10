Configure the hosts file
```bash
$ sudo nano /etc/hosts
```
Add new line
```bash
127.0.0.1 try-rest.local
```
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
Install dependencies
```bash
$ docker exec --user dev try_rest_php_1 composer install
```
To get in to the container as non root user run
```bash
$ docker exec -it try_rest_php_1 su dev
```
