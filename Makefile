.SILENT:
.NOTPARALLEL:

## Settings
.DEFAULT_GOAL := inside

inside:
	docker exec -it php82-dto /bin/bash
.PHONY: inside

up:
	docker-compose up -d
.PHONY: up

down:
	docker-compose down
.PHONY: down

test-ok:
	docker exec -it php82-dto ./vendor/bin/phpunit --group ok
.PHONY: test-ok

test+:
	docker exec -it php82-dto ./vendor/bin/phpunit --group +
.PHONY: +
