.PHONY: help console
bin_dir=vendor/bin

docker/docker-compose.env:
	cp docker/docker-compose.env.dist $@
	sed --in-place "s/{your_unix_local_username}/$(shell whoami)/" $@
	sed --in-place "s/{your_unix_local_uid}/$(shell id -u)/" $@

start: docker/docker-compose.env ## Launch containers
	docker-compose up -d

stop: ## Stop containers
	docker-compose stop

remove: ## Stop and remove containers
	docker-compose stop
	docker-compose rm --force

console: ## Connect to console container
	docker exec -it mmc-fos-user_console /bin/login -p -f $(shell whoami)

vendor/autoload.php: ## Install composer dependencies
	composer install

unit-test: vendor/autoload.php
	${bin_dir}/phpunit --configuration .

test: unit-test ## Launch tests

cs-check: vendor/autoload.php ## Check PHP CS
	${bin_dir}/php-cs-fixer --version
	${bin_dir}/php-cs-fixer fix -v --diff --dry-run

cs-fix: vendor/autoload.php ## Fix PHP CS
	${bin_dir}/php-cs-fixer --version
	${bin_dir}/php-cs-fixer fix -v --diff

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help
