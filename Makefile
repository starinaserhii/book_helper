help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

########################################################################################################################

SHELL:=bash

book_dev_container_name="study_book-helper"

%:
    @:

dev-restart-app:
	docker stop ${book_dev_container_name} || true
	docker compose -f docker-compose.yaml --env-file .env up -t 0 -d --remove-orphans --build

.PHONY: dev-rebuild-restart-app
dev-rebuild-restart-app:
	docker compose -f infrastructure/app/base/docker-compose.yaml --env-file infrastructure/app/book_dev/.env build --no-cache --force-rm
	docker compose -f infrastructure/app/book_dev/docker-compose.yaml --env-file infrastructure/app/book_dev/.env build --no-cache --force-rm
	docker stop ${book_dev_container_name} || true
	docker compose -f infrastructure/app/book_dev/docker-compose.yaml --env-file infrastructure/app/book_dev/.env up -t 0 -d --remove-orphans --build

docker-log:
	docker logs --tail 500 --follow --timestamps ${book_dev_container_name}

shell:
	docker exec -it ${book_dev_container_name} bash

dep-update:
	docker exec -it ${book_dev_container_name} bash -c 'composer update'

messenger-setup-transports:
	docker exec -it ${book_dev_container_name} bash -c 'php bin/console messenger:setup-transports'

dev-phpunit:
	docker exec -it ${book_dev_container_name} bash -c 'bin/phpunit --exclude-group sql'

## для отрисовки страницы Swagger выполнить установку assets
swagger-assets-install:
	docker exec -it ${book_dev_container_name} bash -c 'php bin/console assets:install'