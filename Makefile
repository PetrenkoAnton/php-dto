.SILENT:
.NOTPARALLEL:

## Settings
.DEFAULT_GOAL := inside

inside:
	docker exec -it php-dto /bin/bash
.PHONY: inside

up80:
	docker-compose -f .docker/php80/docker-compose.yml up -d
.PHONY: up80

up81:
	docker-compose -f .docker/php81/docker-compose.yml up -d
.PHONY: up81

up82:
	docker-compose -f .docker/php82/docker-compose.yml up -d
.PHONY: up82

up83:
	docker-compose -f .docker/php83/docker-compose.yml up -d
.PHONY: up83

down:
	docker stop php-dto && docker rm php-dto
.PHONY: down

php-v:
	docker exec -it php-dto php -v
.PHONY: php -v

v:
	docker exec -it php-dto cat VERSION
.PHONY: v

test:
	docker exec -it php-dto ./vendor/bin/phpunit
.PHONY: test

composer-test:
	docker exec -it php-dto composer test
.PHONY: composer-test

test-ok:
	docker exec -it php-dto ./vendor/bin/phpunit --group ok
.PHONY: test-ok

test+:
	docker exec -it php-dto ./vendor/bin/phpunit --group +
.PHONY: test+
