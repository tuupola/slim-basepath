.DEFAULT_GOAL := help

help:
	@echo ""
	@echo "Available tasks:"
	@echo "    slim-4  Switch to Slim 4"
	@echo "    slim-3  Switch to Slim 3"
	@echo "    vendor  Install dependencies"
	@echo "    clean   Remove vendor and composer.lock"
	@echo ""

vendor: $(wildcard composer.lock)
	composer install --prefer-dist

lint: vendor
	vendor/bin/phplint . --exclude=vendor/
	vendor/bin/phpcs -p --standard=PSR2 --extensions=php --encoding=utf-8 --ignore=*/vendor/*,*/benchmarks/* .

clean:
	-rm -rf vendor
	-rm composer.lock

slim-4:
	make clean
	git checkout slim-4
	make vendor

slim-4-nyholm:
	make clean
	git checkout slim-4-nyholm
	make vendor

slim-4-diactoros:
	make clean
	git checkout slim-4-diactoros
	make vendor

slim-3:
	make clean
	git checkout slim-3
	make vendor

.PHONY: help slim-3 slim-4 slim-4-nyholm clean
