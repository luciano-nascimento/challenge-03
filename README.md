
# Motivation   
Challenge proposed by Jumia
<br/>

# Installation (via docker)

- first of all build and start container   
```sh
$ docker-compose up -d
```
<br/>

# Tests
Run tests using
```sh
docker-compose exec app php artisan test
```
<br/>

# Consume API
set Accept as application/json


# Improvments

* env file should not be here
* should add authentication to api
* add api documentation like swagger for example
* force return response as json
* would be good have producer and consumer in differents ms