# Help
DOCKER_COMPOSE = docker-compose exec php

# Tests
.PHONY: test
test: cs phpunit

.PHONY: phpunit
phpunit: bin
	$(DOCKER_COMPOSE) ./vendor/bin/simple-phpunit --stop-on-error --stop-on-failure --testdox

# Coding Style
.PHONY: cs cs-fix
cs: vendor ## Check code style
	$(DOCKER_COMPOSE) ./vendor/bin/php-cs-fixer fix --config=.php_cs.dist --dry-run --diff --verbose --allow-risky=yes

cs-fix: vendor ## Fix code style
	$(DOCKER_COMPOSE) ./vendor/bin/php-cs-fixer fix --config=.php_cs.dist --verbose --allow-risky=yes
