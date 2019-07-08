.PHONY: help
.DEFAULT_GOAL := help

$(VERBOSE).SILENT:

help:
    grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
    cut -d: -f2- | \
    sort -d | \
    awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'

.PHONY: up down test phpstan cs cs-fix composer-install composer-update release

up:
	docker-compose -f docker-compose.examples.yml stop --timeout=2 && docker-compose -f docker-compose.examples.yml up -d

down:
	docker-compose -f docker-compose.examples.yml stop --timeout=2

test:
	docker-compose up

phpstan:
	docker-compose run --rm yin-php /bin/bash -c "cd /var/www && ./vendor/bin/phpstan analyse --level 7 src tests"

cs:
	docker-compose run --rm yin-php /var/www/vendor/bin/phpcs --standard=/var/www/phpcs.xml

cs-fix:
	docker-compose run --rm yin-php /var/www/vendor/bin/phpcbf --standard=/var/www/phpcs.xml

composer-install:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(id -u):$(id -g) composer install --ignore-platform-reqs

composer-update:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(id -u):$(id -g) composer update --ignore-platform-reqs

release: test phpstan cs
	./vendor/bin/releaser release
