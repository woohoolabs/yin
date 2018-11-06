.PHONY: help
.DEFAULT_GOAL := help

$(VERBOSE).SILENT:

help:
    grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
    cut -d: -f2- | \
    sort -d | \
    awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'

.PHONY: up down composer-install composer-update test

up:
	docker-compose -f docker-compose.examples.yml stop --timeout=2 && docker-compose  -f docker-compose.examples.yml up -d

down:
	docker-compose -f docker-compose.examples.yml stop --timeout=2

composer-install:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(id -u):$(id -g) composer install --ignore-platform-reqs

composer-update:
	docker run --rm --interactive --tty --volume $(PWD):/app --user $(id -u):$(id -g) composer update --ignore-platform-reqs

test:
	docker-compose -f docker-compose.yml up

cs:
	docker-compose -f docker-compose.yml run yin-php /var/www/vendor/bin/phpcs \
	    --standard=/var/www/phpcs.xml \
	    --encoding=UTF-8 \
	    --report-full \
	    --extensions=php \
	   /var/www/src/ /var/www/tests/

cs-fix:
	docker-compose -f docker-compose.yml run yin-php /var/www/vendor/bin/phpcbf \
	    --standard=/var/www/phpcs.xml \
	    --extensions=php \
	   /var/www/src/ /var/www/tests/
