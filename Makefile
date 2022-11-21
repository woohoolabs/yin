.PHONY: help
.DEFAULT_GOAL := help
$(VERBOSE).SILENT:

help: ## Print the help screen
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

up: ## Start the Docker-based web server in order to try out the examples
	docker-compose -f docker-compose.examples.yml stop --timeout=2 && docker-compose -f docker-compose.examples.yml up -d

down: ## Stop the Docker-based web server
	docker-compose -f docker-compose.examples.yml stop --timeout=2

composer-install: ## Install Composer dependencies
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(id -u):$(id -g) composer install --ignore-platform-reqs

composer-update: ## Update Composer dependencies
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(id -u):$(id -g) composer update --ignore-platform-reqs

test: ## Run PHPUnit for the unit tests
	docker-compose run --rm --no-deps yin-php /bin/bash -c "cd /var/www && ./vendor/bin/phpunit $(if $(TEST),$(TEST),)"

phpstan: ## Run PHPStan to perform static analysis
	docker-compose run --rm --no-deps yin-php /bin/bash -c "cd /var/www && ./vendor/bin/phpstan"

cs: ## Run PHP CodeSniffer to detect issues with coding style
	docker-compose run --rm --no-deps yin-php /var/www/vendor/bin/phpcs --standard=/var/www/phpcs.xml

cs-fix: ## Run PHPCBF to automatically fix issues with coding style
	docker-compose run --rm --no-deps yin-php /var/www/vendor/bin/phpcbf --standard=/var/www/phpcs.xml

qa: test phpstan cs ## Test code quality

release: qa ## Release a new version of the library
	./vendor/bin/releaser release
