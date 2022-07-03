# Supervisors Code Challange
## About the Stack and code
  - Dockerized with Docker Compose version v2.3.3
  - Code was written and tested on Ubuntu 20.04 
  - The Stack is [Slim4 Framework](https://www.slimframework.com/) 
  - Code is in PHP
  There is no need for a database, however there is commented code in the Dockerfile located at docker/php/Dockerfile that will install either mySQL or PostgreSQL on the Docker image 

## Run Requirements
  - Docker Compose installed globally
  - Docker Daemon must be running.
	- [Composer](https://getcomposer.org/). 
  
Here are some useful installation notes on [Docker Compose](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-22-04).

## Instructions
  - Download the git repository to your chosen destination
  `$ git clone https://github.com/jpickett76/supervisors.git`
  - CD into the supervisors folder
  `$ cd supervisors`
  - Edit the supervisors/php/Dockerfile line 24 & 25 with your github email and name
  - Depending upon your system setup you may need to run the following for composer to properly run slim4
  ```
  $ composer require slim/slim:"4.*" \slim/psr7 \ selective/basepath
  $ composer require laminas/laminas-diactoros
  ```
  - Build and run as deamon
  `$ docker compose up -d --build`
  
  
## API Endpoints are
  GET http://localhost:8080/api/supervisors
  POST http://localhost:8080/api/submit
  Post parameters below should to be in the body of the post and as a JSON String.  
  This will pass:
  [{"firstName": "Bob", "lastName": "Smith", "email": "bob.smith@bobsmith.com", "phoneNumber": "1115551212", "supervisor": "Mike Johnson"}]