help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

########################################################################################################################

SHELL:=bash

container_name="book-helper-mysql"

up:
	docker network create book-helper-network || true
	source .env && sudo find $$BH_APP_MYSQL_HOST_STORAGE_PATH -type d -exec chmod 777 {} \;
	source .env && sudo find $$BH_APP_MYSQL_HOST_STORAGE_PATH -type f -exec chmod 666 {} \;
	docker-compose -f docker-compose.yaml up -t 0 -d --build

down:
	docker stop ${container_name}

shell:
	docker exec -it ${container_name} bash

log:
	docker logs --tail 500 --follow --timestamps ${container_name}
