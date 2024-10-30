help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

########################################################################################################################

SHELL:=bash

dev_container_name="study_book-helper"

%:
    @:

dev-restart-app:
	docker stop ${dev_container_name} || true
	docker compose -f docker-compose.yaml --env-file .env up -t 0 -d --remove-orphans --build

.PHONY: dev-rebuild-restart-app
dev-rebuild-restart-app:
	docker compose -f infrastructure/app/base/docker-compose.yaml --env-file infrastructure/app/dev/.env build --no-cache --force-rm
	docker compose -f infrastructure/app/dev/docker-compose.yaml --env-file infrastructure/app/dev/.env build --no-cache --force-rm
	docker stop ${dev_container_name} || true
	docker compose -f infrastructure/app/dev/docker-compose.yaml --env-file infrastructure/app/dev/.env up -t 0 -d --remove-orphans --build

.PHONY: dev-rebuild-restart-all
dev-rebuild-restart-all:
	docker stop $$(docker ps -q) || true
	docker rm $$(docker ps -a -q) || true
	docker network rm book-helper-network || true
	docker network create book-helper-network
	sudo find data/mysql -type d -exec chmod 777 {} \;
	sudo find data/mysql -type f -exec chmod 666 {} \;
	docker compose -f infrastructure/mysql/docker-compose.yaml up -t 0 -d
	docker compose -f infrastructure/elasticsearch/docker-compose.yaml up -t 0 -d
	docker compose -f infrastructure/rabbitmq/docker-compose.yaml up -t 0 -d --build
	docker compose -f infrastructure/app/base/docker-compose.yaml --env-file infrastructure/app/dev/.env build --no-cache --force-rm
	docker compose -f infrastructure/app/dev/docker-compose.yaml --env-file infrastructure/app/dev/.env build --no-cache --force-rm
	docker compose -f infrastructure/app/dev/docker-compose.yaml --env-file infrastructure/app/dev/.env up -t 0 -d --remove-orphans --build

dev-apply-migrations:
	docker exec -it ${dev_container_name} bash -c 'php bin/console doctrine:migrations:migrate --configuration=src/Core/Context/AppInstance/Infrastructure/Persistence/Doctrine/migrations.yaml --no-interaction'
	docker exec -it ${dev_container_name} bash -c 'php bin/console doctrine:migrations:migrate --configuration=src/Core/Context/Matching/Infrastructure/Persistence/Doctrine/migrations.yaml --no-interaction'
	docker exec -it ${dev_container_name} bash -c 'php bin/console doctrine:migrations:migrate --configuration=src/Core/Context/MetafieldPublisher/Infrastructure/Persistence/Doctrine/migrations.yaml --no-interaction'
	docker exec -it ${dev_container_name} bash -c 'php bin/console doctrine:migrations:migrate --configuration=src/Core/Context/SupportInquire/Infrastructure/Persistence/Doctrine/migrations.yaml --no-interaction'
	docker exec -it ${dev_container_name} bash -c 'php bin/console doctrine:migrations:migrate --configuration=src/Core/Context/OwnerStatistics/Infrastructure/Persistence/Doctrine/migrations.yaml --no-interaction'
	docker exec -it ${dev_container_name} bash -c 'php bin/console doctrine:migrations:migrate --configuration=src/Core/Context/Affiliate/Infrastructure/Persistence/Doctrine/migrations.yaml --no-interaction'

build-migrations:
	source infrastructure/app/prod/.env && docker exec -it ${dev_container_name} bash -c "bin/build_migrations.sh $${HREF_APP_DATABASE_URL}"
	cat app/backend/var/migrations/up* > build/backend/v$(filter-out $@,$(MAKECMDGOALS))/migration_up.sql
	cat app/backend/var/migrations/down* > build/backend/v$(filter-out $@,$(MAKECMDGOALS))/migration_down.sql

release: revert-permissions
	docker stop $$(docker ps -q) || true
	bin/release.sh $(filter-out $@,$(MAKECMDGOALS))

docker-log:
	docker logs --tail 500 --follow --timestamps ${dev_container_name}

shell:
	docker exec -it ${dev_container_name} bash

dep-update:
	docker exec -it ${dev_container_name} bash -c 'composer update'

messenger-setup-transports:
	docker exec -it ${dev_container_name} bash -c 'php bin/console messenger:setup-transports'

messenger-stats:
	docker exec -it ${dev_container_name} bash -c 'php bin/console messenger:stats'

messenger-failed-retry:
	docker exec -it ${dev_container_name} bash -c 'for counter in {1..$(filter-out $@,$(MAKECMDGOALS))}; do php /app/bin/console messenger:failed:retry --force; done'

dev-phpunit:
	docker exec -it ${dev_container_name} bash -c 'bin/phpunit --exclude-group sql'

jwt-generate:
	docker exec -it ${dev_container_name} bash -c 'php bin/console lexik:jwt:generate-keypair'

## для отрисовки страницы Swagger выполнить установку assets
swagger-assets-install:
	docker exec -it ${dev_container_name} bash -c 'php bin/console assets:install'

test-dep-graphs-install:
	docker exec -it ${dev_container_name} bash -c 'apt install graphviz'

.PHONY: test-dep-graphs
test-dep-graphs: test-dep-classes-graph test-dep-components-graph test-dep-layers-graph

test-dep-classes:
	docker exec -it ${dev_container_name} bash -c './vendor/bin/deptrac --config-file=deptrac.classes.yaml'

.PHONY: test-dep-classes-graph
test-dep-classes-graph:
	docker exec -it ${dev_container_name} bash -c './vendor/bin/deptrac --config-file=deptrac.classes.yaml --formatter=graphviz-image --output=etc/deptrac/dep-classes.png' || true

test-dep-components:
	docker exec -it ${dev_container_name} bash -c './vendor/bin/deptrac --config-file=deptrac.components.yaml'

.PHONY: test-dep-components-graph
test-dep-components-graph:
	docker exec -it ${dev_container_name} bash -c './vendor/bin/deptrac --config-file=deptrac.components.yaml --formatter=graphviz-image --output=etc/deptrac/dep-components.png' || true

test-dep-layers:
	docker exec -it ${dev_container_name} bash -c './vendor/bin/deptrac --config-file=deptrac.layers.yaml'

.PHONY: test-dep-layers-graph
test-dep-layers-graph:
	docker exec -it ${dev_container_name} bash -c './vendor/bin/deptrac --config-file=deptrac.layers.yaml --formatter=graphviz-image --output=etc/deptrac/dep-layers.png' || true

.PHONY: revert-permissions
revert-permissions:
	sudo chown -R $$(id -u):$$(id -g) .

.PHONY: secrets-encrypt
secrets-encrypt:
	gpg -c infrastructure/secrets.txt

.PHONY: secrets-decrypt
secrets-decrypt:
	gpg -o infrastructure/secrets.txt -d infrastructure/secrets.txt.gpg
