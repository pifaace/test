# Api-test

To access to the api swagger doc, go to *http://localhost:8000/api*

### Installing

#### Clone the project
```
$ git clone git@github.com:pifaace/test.git
```

#### Run dependencies and running containers
```
$ docker-compose build
$ docker-compose run --rm --no-deps php composer install
$ docker-compose up -d
```

### Migrations
If you get a "Connection refused" wait a few seconds and try again
```
$ docker-compose exec php bin/console doctrine:migrations:migrate
$ docker-compose exec php bin/console hautelook:fixtures:load
```

### Running tests
```
$ make test
```
